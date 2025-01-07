<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\Role;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\Order;
use Modules\NsGastro\Services\KitchenService;
use Tests\TestCase;
use Tests\Traits\WithOrderTest;

class GastroCookOrderFromKitchen extends TestCase
{
    use WithOrderTest;

    protected $count = 1;

    protected $totalDaysInterval = 1;

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

        $this->attemptCreateHoldOrder();

        /**
         * @var KitchenService
         */
        $kitchenService = app()->make(KitchenService::class);

        /**
         * @var Order
         */
        $order = Order::orderBy('id', 'desc')
            ->where('payment_status', Order::PAYMENT_HOLD)
            ->first();

        $order->products->each(function ($product) use ($kitchenService, $order) {
            $kitchen = $kitchenService->getKitchenFromCategory($product->product_category_id);

            if ($kitchen instanceof Kitchen) {
                /**
                 * this will send the cook request for the meal
                 */
                $response = $this
                    ->withSession($this->app['session']->all())
                    ->json('POST', '/api/gastro/kitchens/'.$kitchen->id.'/cook/'.$order->id, [
                        'products'  =>  [$product->id],
                    ]);

                $response->assertJsonPath('status', 'success');
            }
        });

        $order->refresh();

        $this->assertEquals(Order::PAYMENT_HOLD, $order->payment_status);
    }
}
