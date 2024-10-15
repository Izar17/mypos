<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\Role;
use App\Services\OrdersService;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\Order;
use Modules\NsGastro\Models\OrderProduct;
use Modules\NsGastro\Services\KitchenService;
use Tests\TestCase;
use Tests\Traits\WithOrderTest;

class GastroCancelOrderFromKitchen extends TestCase
{
    use WithOrderTest;

    protected $count = 1;

    protected $totalDaysInterval = 1;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCancelOrder()
    {
        Sanctum::actingAs(
            Role::namespace('admin')->users->first(),
            ['*']
        );

        $this->attemptCreateHoldOrder();

        /**
         * @var KitchenService
         */
        $kitchenService = app()->make(KitchenService::class);

        /**
         * @var OrdersService
         */
        $orderService = app()->make(OrdersService::class);

        /**
         * @var Order
         */
        $order = Order::orderBy('id', 'desc')->first();

        $invalidProducts = $order->products->filter(fn ($product) => $product->product_category_id == 0)->count();

        $order->products->each(function ($product) use ($kitchenService, $order) {
            $kitchen = $kitchenService->getKitchenFromCategory($product->product_category_id);

            if ($kitchen instanceof Kitchen) {
                /**
                 * this will send the cook request for the meal
                 */
                $response = $this
                    ->withSession($this->app['session']->all())
                    ->json('POST', '/api/gastro/kitchens/'.$kitchen->id.'/cancel/'.$order->id, [
                        'products'  =>  [$product->id],
                    ]);

                $response->assertJsonPath('status', 'success');
            }
        });

        $order->refresh();

        $canceledCount = $order->products
            ->filter(fn ($product) => $product->cooking_status === OrderProduct::COOKING_CANCELED)
            ->count();

        $this->assertTrue(($order->products->count() - $invalidProducts) === $canceledCount, 'The canceled products doesn\'t match the order products.');

        $taxValue = $order->tax_value;

        $orderService->computeOrderTaxes($order);

        $this->assertEquals($taxValue, $order->tax_value, __('The tax value is not computed correctly'));
    }
}
