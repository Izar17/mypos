<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\Customer;
use App\Models\OrderPayment;
use App\Models\Role;
use App\Services\OrdersService;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Order;
use Modules\NsGastro\Models\OrderProduct;
use Modules\NsGastro\Models\OrderProductModifierGroup;
use Modules\NsGastro\Models\Product;
use Modules\NsGastro\Models\Table;
use Modules\NsGastro\Models\TableSession;
use Modules\NsGastro\Services\GastroOrderService;
use Modules\NsGastro\Services\TableService;
use stdClass;
use Tests\TestCase;
use Tests\Traits\WithOrderTest;

class GastroTestMoveOrder extends TestCase
{
    use WithOrderTest;

    protected $count = 1;

    protected $ordersCount = 0;

    protected $totalDaysInterval = 1;

    protected $session;

    protected $shouldRefund = false;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCookingOrder()
    {
        Sanctum::actingAs(
            Role::namespace('admin')->users->first(),
            ['*']
        );

        $requiredTableCount = 3; // Number of tables required
        $availableTablesCount = Table::busy(false)->count();

        if ($availableTablesCount < $requiredTableCount) {
            $this->fail("Expected $requiredTableCount tables, but found {$availableTablesCount} tables.");
        }

        /**
         * @var TableService $gastroTableService
         */
        $gastroTableService = app()->make(TableService::class);

        /**
         * let's truncate all session to be
         * sure session are created
         */
        TableSession::truncate();

        /**
         * We want to close all tables
         */
        Table::get()
            ->each(fn ($table) => $gastroTableService
                ->changeTableAvailability($table, Table::STATUS_AVAILABLE)
            );

        /**
         * let's enable de required options
         */
        ns()->option->set('ns_gastro_freed_table_with_payment', true);
        ns()->option->set('ns_gastro_enable_table_sessions', true);

        /**
         * we'll define a order Params
         */
        $table = Table::busy(false)->first();

        /**
         * Because we expect the table to have
         * the attribute "selected" set to yes.
         */
        $table->selected = true;

        $this->customOrderParams = [
            'table'                 =>  $table->toArray(),
            'gastro_order_status'   =>  'pending',
        ];

        /**
         * first attempt to post an order
         * over a table.
         */
        $orderDetails   =   [
            'table'                 =>  $table->toArray(),
            'gastro_order_status'   =>  'pending',
            'products'              =>  $this->getProductsWithModifiers(),
        ];

        $response   = $this->processOrders(
            orderDetails: $orderDetails,
        );

        $order   =   $response[0][ 'order-creation' ][ 'data' ][ 'order' ];
        $order = Order::find( $order[ 'id' ] );

        $this->assertTrue($order->table_id === $table->id, 'The order is not assigned to a table.');
        $this->assertTrue($order->gastro_order_status === 'pending', 'The order is not in the right status.');

        $this->session = TableSession::findOrFail($order->gastro_table_session_id);
        $this->ordersCount = $this->session->orders->count();
        $belongToSession = $this->session->orders->filter(fn ($_order) => $_order->id === $order->id)->count();

        $this->assertTrue($belongToSession > 0, 'The order is not attached to a session');

        $orderProducts   =   OrderProduct::with( 'modifiers.group' )->where( 'order_id', $order->id )->get();
        
        $orderProducts->each( function( $orderProduct, $index ) use ( $orderDetails ) {
            $originalProduct    =   $orderDetails[ 'products' ][ $index ];

            collect( $originalProduct[ 'modifiersGroups' ] )->each( function( $modifierGroup ) use ( $orderProduct ) {
                $orderProductModifierGroup  =   OrderProductModifierGroup::where( 'order_product_id', $orderProduct->id )
                    ->where( 'modifier_group_id', $modifierGroup[ 'modifier_group_id' ] )
                    ->first();
                $this->assertTrue( $orderProductModifierGroup instanceof OrderProductModifierGroup, 'The order product doesn\'t have modifiers.' );
            } );

            $orderProductModifierGroup  =   OrderProductModifierGroup::where( 'order_product_id', $orderProduct->id )->get();
            $this->assertTrue( $orderProduct->modifiers->count() > 0, 'The order product doesn\'t have modifiers.' );
        } );

        /**
         * We'll make a payment for each orders
         * and check if they are closed
         *
         * @var OrdersService $orderService
         */
        $orderService = app()->make(OrdersService::class);

        /**
         * @var GastroOrderService $gastroOrderService
         */
        $gastroOrderService = app()->make(GastroOrderService::class);
        $orderPayment = OrderPayment::first();

        $this->session
            ->orders()
            ->get()
            ->each(function ($order) use ($orderService, $orderPayment) {
                // we'll make a full payment for that order
                if ($order->payment_status !== Order::PAYMENT_PAID) {
                    $orderService->makeOrderSinglePayment([
                        'value'         =>  $order->total,
                        'identifier'    =>  $orderPayment->identifier,
                    ], $order);
                }
            });

        $this->session->refresh();
        $table->refresh();

        $this->assertTrue((bool) $table->busy === false, 'The table hasn\'t been freed');
        $this->assertTrue((bool) $this->session->active === false, 'The session hasn\'t been closed with orders payment.');

        $newOrder = $this->session
            ->orders()
            ->first();

        $chunks = $newOrder
            ->products()
            ->with( 'modifiers' )
            ->get()
            ->chunk(2);

        $storedModifiers    =   $newOrder->products->map( function( $product ) {
            return $product->modifiers->map( function( $modifier ) {
                return $modifier->toArray();
            });
        });

        $firstTable = Table::busy(false)->get()->random();
        $secondTable = Table::busy(false)->get()->random();
        $customer = Customer::first();

        /**
         * let's now try to split the order
         */
        $result = $gastroOrderService->splitOrders([
            'original'  =>  $newOrder,
            'slices'    =>  [
                [
                    'table_id'  =>  $firstTable->id,
                    'customer_id'   =>  $customer->id,
                    'type'          =>  'dine-in',
                    'products'  =>  $chunks->first()->toArray(),
                ], [
                    'table_id'  =>  $secondTable->id,
                    'customer_id'   =>  $customer->id,
                    'type'          =>  'dine-in',
                    'products'  =>  $chunks->last()->toArray(),
                ],
            ],
        ]);

        $orders = $result['data'];

        /**
         * Let's check if the modifiers are
         * correctly assigned to the order
         */
        $index  =   0;

        /**
         * When we split an order, we would still like to check
         * if the modifiers are correctly assigned to each order product
         */
        $orders->each( function( $sliceResult ) use ( $storedModifiers, &$index ) {
            /**
             * let's check if the first chunks
             * includes the modifiers
             */
            $order  =   Order::with( 'products.modifiers.group' )
                ->find( $sliceResult[ 'data' ][ 'order' ]->id );

            $order->products->each( function( $product ) use ( $storedModifiers, &$index ) {
                $product->modifiers->each( function( $modifier ) use ( $index, $storedModifiers ) {
                    $storedModifiersIndexes =   collect( $storedModifiers[ $index ] )->map( fn( $mod ) => $mod[ 'modifier_id' ] )->toArray();
                    $this->assertTrue( in_array( $modifier->modifier_id, $storedModifiersIndexes ) );
                });

                $index++;
            });
        });
        
        $newOrders = $orders->map(function ($data, $index) use ( $firstTable, $secondTable, $customer ) {
            if ($index === 0) {
                $this->assertTrue(
                    (int) $data['data']['order']->table_id === (int) $firstTable->id,
                    __m('The first table hasn\'t been assigned to the order.', 'NsGastro')
                );

                $this->assertTrue(
                    (int) $data['data']['order']->customer_id === (int) $customer->id,
                    __m('The wrong customer has been assigned to the order.', 'NsGastro')
                );
            } else {
                $this->assertTrue(
                    (int) $data['data']['order']->table_id === (int) $secondTable->id,
                    __m('The second table hasn\'t been assigned to the order.', 'NsGastro')
                );

                $this->assertTrue(
                    (int) $data['data']['order']->customer_id === (int) $customer->id,
                    __m('The wrong customer has been assigned to the order.', 'NsGastro')
                );
            }

            return $data['data']['order'];
        })->toArray();

        /**
         * Let's now try to merge the orders
         */
        $newTable = Table::whereNotIn('id', [$firstTable->id, $secondTable->id])->first();
        $newCustomer = Customer::whereNotIn('id', [$customer->id])->first();

        $result = $gastroOrderService->mergeOrders($newOrders->toArray(), [
            'table_id'      =>  $newTable->id,
            'customer_id'   =>  $newCustomer->id,
            'type'          =>  'dine-in',
            'name'          =>  __m('Merged Order', 'NsGastro'),
        ]);

        $this->assertTrue(
            (int) $result['data']['order']->table_id === (int) $newTable->id,
            __m('The new table is not assigned to the merged order.', 'NsGastro')
        );

        $this->assertTrue(
            (int) $result['data']['order']->customer_id === (int) $newCustomer->id,
            __m('The new customer is not assigned to the merged order.', 'NsGastro')
        );

        $this->assertSame(
            Order::PAYMENT_HOLD,
            $result['data']['order']->payment_status,
            __m('The new order is not in hold status.', 'NsGastro')
        );
    }

    public function getProductsWithModifiers( $count = 3 )
    {
        $rawProducts   =   Product::withStockDisabled()->with('modifiersGroups')->has( 'modifiersGroups' )->limit( $count )->get();

        return $rawProducts->map(function ($product) {
            $unitElement = $product->unit_quantities->first();

            $data = [
                'quantity'              =>  1,
                'unit_price'            =>  $unitElement->sale_price,
                'tax_type'              =>  'inclusive',
                'discount_type'         =>  null,
                'tax_group_id'          =>  1,
                'product_id'            =>  $product->id,
                'unit_id'               =>  $unitElement->unit_id,
            ];

            $data['unit_quantity_id'] = $unitElement->id;
            $data[ 'modifiersGroups' ]  =   $product->modifiersGroups->map( function( $modifierGroup ) {
                $modifierGroup->modifiers = $modifierGroup->modifiers()->take(1)->get()->map( function( $modifierProduct ) use ( $modifierGroup ) {
                    $modifier   = new stdClass;
                    $modifier->selected                     = true;
                    $modifier->unit_price                   =   $modifierProduct->unit_quantities()->first()->sale_price;
                    $modifier->quantity                     =   1;
                    $modifier->name                         =   $modifierProduct->name;
                    $modifier->unit_quantity_id             =   $modifierProduct->unit_quantities()->first()->id;
                    $modifier->unit_id                      =   $modifierProduct->unit_quantities()->first()->unit_id;
                    $modifier->modifier_id                  =   $modifierProduct->id;
                    $modifier->modifier_group_id            =   $modifierGroup->id;
                    $modifier->tax_value                    =   0;
                    $modifier->total_price                  =   $modifierProduct->unit_quantities()->first()->sale_price;

                    unset( $modifier->id );

                    return $modifier;
                } );

                $group  =   $modifierGroup->toArray();

                unset( $group[ 'id' ] );

                $group[ 'modifier_group_id' ]   =   $modifierGroup->id;

                return $group;

            } )->toArray();

            return $data;
        });
    }
}
