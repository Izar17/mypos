<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Order;
use Modules\NsGastro\Models\Table;
use Modules\NsGastro\Models\TableSession;
use Modules\NsGastro\Services\GastroOrderService;
use Tests\TestCase;
use Tests\Traits\WithOrderTest;

class GastroTestPrintedItemsAreHidden extends TestCase
{
    use WithFaker, WithOrderTest;

    public $session;
    public $ordersCount;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testOrderVisibility()
    {
        Sanctum::actingAs(
            Role::namespace('admin')->users->first(),
            ['*']
        );

        /**
         * We don't want quick product to be saved.
         */
        $this->allowQuickProducts = false;

        /**
         * let's truncate all session to be
         * sure session are created
         */
        TableSession::truncate();
        Table::get()->each(function ($table) {
            $table->busy = false;
            $table->save();
        });

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
         * Because we expect the table to have
         * the attribute "selected" set to yes.
         */
        $table->selected = true;

        $orderDetails = [
            'table'                 =>  $table->toArray(),
            'gastro_order_status'   =>  'pending',
        ];

        /**
         * first attempt to post an order
         * over a table.
         */
        $this->processOrders( $orderDetails, function ($response, $data) use ($table) {
            $order = Order::find($data['data']['order']['id']);

            $this->assertTrue($order->table_id == $table->id, 'The order is not assigned to a table.');
            $this->assertTrue($order->gastro_order_status === 'pending', 'The order is not in the right status.');

            $this->session = TableSession::findOrFail($order->gastro_table_session_id);
            $this->ordersCount = $this->session->orders->count();
            $belongToSession = $this->session->orders->filter(fn ($_order) => $_order->id === $order->id)->count();

            $this->assertTrue($belongToSession > 0, 'The order is not attached to a session');

            /**
             * @var GastroOrderService
             */
            $gastroOrderService = app()->make(GastroOrderService::class);
            $receipts = $gastroOrderService->getKitchensProducts($order);

            $this->assertTrue(! $receipts->isEmpty(), 'The order receipt for the first attempt is empty.');

            $receipts = $gastroOrderService->getKitchensProducts($order);

            $this->assertTrue($receipts->isEmpty(), 'The order receipt for the second attempt is not empty.');

            /**
             * We'll try to add a new product and see if that product
             * get added to the order
             */
            $products = Product::with('unit_quantities')->withStockDisabled()->get()->shuffle()->take(3);
            $products = $products->map(function ($product) {
                $unitElement = $this->faker->randomElement($product->unit_quantities);
                $data = [
                    'name'              =>  'Fees',
                    'quantity'          =>  $this->faker->numberBetween(1, 10),
                    'product_id'        =>  $product->id,
                    'unit_price'        =>  $unitElement->sale_price,
                    'tax_type'          =>  'inclusive',
                    'tax_group_id'      =>  1,
                    'mode'              =>  'normal',
                    'tax_value'         =>  0,
                    'unit_id'           =>  $unitElement->unit_id,
                    'unit_quantity_id'  =>  $unitElement->id,
                ];

                if ($this->faker->randomElement([false, true])) {
                    $data['product_id'] = $product->id;
                    $data['unit_quantity_id'] = $unitElement->id;
                }

                return $data;
            })->filter(function ($product) {
                return $product['quantity'] > 0;
            });

            $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/gastro/orders/'.$order->id.'/add-products', [
                    'products'      =>      $products,
                ]);

            $response->assertStatus(200);
            $responseData = json_decode($response->getContent(), true);

            /**
             * let's test if the product
             * added to the receipt
             */
            $receipts = $gastroOrderService->getKitchensProducts($order);

            collect($receipts)->each(function ($receipt) use ($responseData) {
                $printedProducts = collect($responseData['data']['orderProducts'])->map(fn ($product) => $product['id'])->toArray();

                collect($receipt['products'])->each(function ($product) use ($printedProducts) {
                    $this->assertTrue(in_array($product['id'], $printedProducts), __('The printed product doesn\'t belong to the product send to the kitchen'));
                });
            });

            $this->assertTrue(! empty($receipts), 'The order receipt for the first attempt is empty.');
        });

        return false;
    }
}
