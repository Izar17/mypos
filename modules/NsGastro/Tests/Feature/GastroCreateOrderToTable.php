<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\Role;
use App\Services\OrdersService;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\Order;
use Modules\NsGastro\Models\Table;
use Modules\NsGastro\Models\TableSession;
use Modules\NsGastro\Services\GastroOrderService;
use Modules\NsGastro\Services\KitchenService;
use Tests\TestCase;
use Tests\Traits\WithOrderTest;

class GastroCreateOrderToTable extends TestCase
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
         * we'll define a define order Params
         */
        $table = Table::busy(false)->first();

        /**
         * @var GastroOrderService
         */
        $gastroOrderService = app()->make(GastroOrderService::class);

        /**
         * @var KitchenService
         */
        $gastroKitchenService = app()->make(KitchenService::class);

        /**
         * Because we expect the table to have
         * the attribute "selected" set to yes.
         */
        $table->selected = true;

        $orderDetails = [
            'table'                 =>  $table->toArray(),
            'gastro_order_status'   =>  'pending',
            'productsRequest'       =>  function() {
                return Product::notInGroup()->limit(3)->get();
            }
        ];

        /**
         * first attempt to post an order
         * over a table.
         */
        $this->shouldMakePayment = false;

        $this->processOrders( $orderDetails, function ($response, $data) use ($table, $gastroOrderService, $gastroKitchenService) {
            $order = Order::find($data['data']['order']['id']);

            $this->assertTrue((int) $order->table_id == (int) $table->id, 'The order is not assigned to a table.');
            $this->assertTrue($order->gastro_order_status === 'pending', 'The order is not in the right status.');

            $this->session = TableSession::findOrFail($order->gastro_table_session_id);
            $this->ordersCount = $this->session->orders->count();
            $belongToSession = $this->session->orders->filter(fn ($_order) => (int) $_order->id == (int) $order->id)->count();

            $this->assertTrue($belongToSession > 0, 'The order is not attached to a session');

            /**
             * change meal status and check order status
             */

            /**
             * Step 1 : order should be ongoing
             */
            $product = $order->products->first();
            $gastroKitchenService->cookOrderMeal(Kitchen::first(), $order, [$product->id]);

            $order->refresh();

            $this->assertTrue($order->gastro_order_status === Order::COOKING_ONGOING, 'The order status has\'t changed to cooking.');

            /**
             * Step 2 : set other meal as ongoing
             */
            $gastroKitchenService->cookOrderMeal(
                Kitchen::first(),
                $order,
                $order
                    ->products()
                    ->where('id', '<>', $product->id)
                    ->get()
                    ->map(fn ($product) => $product->id)
                    ->toArray()
            );

            /**
             * Step 3: Set all products as ready
             */
            $gastroOrderService->readyOrderMeals(
                $order,
                $order
                    ->products
                    ->map(fn ($product) => $product->id)
                    ->toArray()
            );

            $this->assertTrue($order->gastro_order_status === Order::COOKING_READY, 'The order status has\'t changed to "ready".');
        });

        /**
         * seconds attempt to post an order
         * over the same table
         */
        $this->processOrders( $orderDetails, function ($response, $data) use ($table) {
            $order = Order::find($data['data']['order']['id']);

            $this->assertTrue((int) $order->table_id == (int) $table->id, 'The order is not assigned to a table.');
            $this->assertTrue($order->gastro_order_status === 'pending', 'The order is not in the right status.');

            $tableSession = TableSession::findOrFail($order->gastro_table_session_id);

            $this->assertTrue((int) $this->session->id == (int) $tableSession->id, 'The session aren\'t similar while order where posted on the same table.');
            $this->assertTrue($this->session->orders()->count() - $this->ordersCount === 1, 'The session doesn\'t have the right amount of orders.');

            $belongToSession = $tableSession->orders
                ->filter(fn ($_order) => (int) $_order->id == (int) $order->id)
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
                        'identifier'    =>  $orderPayment->identifier ?? OrderPayment::PAYMENT_CASH,
                    ], $order);
                }
            });

        $this->session->refresh();

        $table->refresh();

        $this->assertTrue((bool) $table->busy === false, 'The table hasn\'t been freed');
        $this->assertTrue((bool) $this->session->active === false, 'The session hasn\'t been closed with orders payment.');
    }
}
