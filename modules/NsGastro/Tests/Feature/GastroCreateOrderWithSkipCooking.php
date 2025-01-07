<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\ProductUnitQuantity;
use App\Models\Role;
use App\Services\OrdersService;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Order;
use Modules\NsGastro\Models\Table;
use Modules\NsGastro\Models\TableSession;
use Modules\NsGastro\Services\TableService;
use Tests\TestCase;
use Tests\Traits\WithOrderTest;

class GastroCreateOrderWithSkipCooking extends TestCase
{
    use WithOrderTest;

    public $session;
    public $ordersCount;

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

        $faker = Factory::create();

        /**
         * let's truncate all session to be
         * sure session are created
         */
        // TableSession::truncate();

        /**
         * let's enable de required options
         */
        ns()->option->set('ns_gastro_freed_table_with_payment', true);
        ns()->option->set('ns_gastro_enable_table_sessions', true);

        /**
         * @var TableService
         */
        $tableService = app()->make(TableService::class);

        Table::get()->each(function ($table) use ($tableService) {
            $tableService->changeTableAvailability($table, Table::STATUS_AVAILABLE);
        });

        /**
         * we'll define a define order Params
         */
        $table = Table::busy(false)->first();

        /**
         * Because we expect the table to have
         * the attribute "selected" set to yes.
         */
        $table->selected = true;

        /**
         * We'll make those product
         * as not requiring to be cooked
         */
        $unitQuantity = ProductUnitQuantity::where('quantity', '>', 0)->get()->shuffle();
        $products = Product::with('unit_quantities')
            ->where('gastro_item_type', 'product')
            // ->whereIn( 'id', $unitQuantity->map( fn( $unitQuantity ) => $unitQuantity->product_id )->toArray() )
            ->get()
            ->shuffle()
            ->take(3);

        $products->each(function ($product) {
            $product->skip_cooking = true;
            $product->save();
        });

        $products = $products->map(function ($product) use ($faker) {
            $unitElement = $faker->randomElement($product->unit_quantities);

            $data = [
                'quantity'              =>  $faker->numberBetween(1, 2),
                'unit_price'            =>  $unitElement->sale_price,
                'tax_type'              =>  'inclusive',
                'tax_group_id'          =>  1,
                'product_id'            =>  $product->id,
                'unit_id'               =>  $unitElement->unit_id,
            ];

            $data['unit_quantity_id'] = $unitElement->id;

            return $data;
        })->filter(function ($product) {
            return $product['quantity'] > 0;
        });

        $orderDetails = [
            'table'                 =>  $table->toArray(),
            'gastro_order_status'   =>  'pending',
            'discount_type'         =>  'flat',
            'discount_percentage'   =>  0,
            'discount'              =>  1,
            'products'              =>  $products,
            'subtotal'              =>  $products->map(function ($product) {
                return $product['unit_price'] * $product['quantity'];
            })->sum(),
        ];

        /**
         * first attempt to post an order
         * over a table.
         */
        $this->shouldMakePayment = false;

        $this->processOrders( $orderDetails, function ($response, $data) use ($table) {
            $order = Order::find($data['data']['order']['id']);

            $this->assertTrue((int) $order->table_id == (int) $table->id, 'The order is not assigned to a table.');
            $this->assertTrue($order->gastro_order_status === Order::COOKING_READY, 'The orders saved with ready meals isn\'t cooked.');

            $this->session = TableSession::findOrFail($order->gastro_table_session_id);
            $this->ordersCount = $this->session->orders->count();
            $belongToSession = $this->session->orders->filter(fn ($_order) => $_order->id === $order->id)->count();

            $this->assertTrue($belongToSession > 0, 'The order is not attached to a session');
        });

        /**
         * seconds attempt to post an order
         * over the same table
         */
        $this->processOrders( $orderDetails, function ($response, $data) use ($table) {
            $order = Order::find($data['data']['order']['id']);

            $this->assertTrue((int) $order->table_id == (int) $table->id, 'The order is not assigned to a table.');

            $tableSession = TableSession::findOrFail($order->gastro_table_session_id);

            $this->assertTrue((int) $this->session->id == (int) $tableSession->id, 'The session aren\'t similar while order where posted on the same table.');
            $this->assertTrue($this->session->orders()->count() - $this->ordersCount === 1, 'The session doesn\'t have the right amount of orders.');
            $this->assertTrue($order->gastro_order_status === Order::COOKING_READY, 'The orders saved with ready meals isn\'t cooked.');

            $belongToSession = $tableSession->orders
                ->filter(fn ($_order) => $_order->id === $order->id)
                ->count();

            $this->assertTrue($belongToSession > 0, 'The order is not attached to a session');
        });

        /**
         * We'll make a payment for each orders
         * and check if they are closed
         *
         * @var OrdersService $orderService
         */
        $orderService = app()->make(OrdersService::class);
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
    }
}
