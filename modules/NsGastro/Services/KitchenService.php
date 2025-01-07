<?php

namespace Modules\NsGastro\Services;

use App\Classes\Hook;
use App\Exceptions\NotAllowedException;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrdersService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Modules\NsGastro\Events\KitchenAfterUpdatedOrderEvent;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\KitchenCategory;
use Modules\NsGastro\Models\Order as GastroOrder;
use Modules\NsGastro\Models\OrderProduct;

class KitchenService
{
    /**
     * @var OrdersService
     */
    private $ordersService;

    private $productService;

    public function __construct(OrdersService $ordersService, ProductService $productService)
    {
        $this->ordersService = $ordersService;
        $this->productService = $productService;
    }

    /**
     * Get kitchen orders (excluding completed orders)
     *
     * @param  Kitchen  $kitchen
     * @param  array  $config
     * @return array result
     */
    public function getOrders(Kitchen $kitchen, $config)
    {
        $kitchenCategories = $kitchen->categories
            ->map(fn ($category) => $category->category_id)
            ->toArray();

        /**
         * Initial request to fetch the orders
         * along with the products, categories and orde products
         */
        $nexopos_orders_products = Hook::filter('ns-model-table', 'nexopos_orders_products');
        $nexopos_products = Hook::filter('ns-model-table', 'nexopos_products');
        $nexopos_products_categories = Hook::filter('ns-model-table', 'nexopos_products_categories');
        $nexopos_orders = Hook::filter('ns-model-table', 'nexopos_orders');

        $request = DB::table($nexopos_orders)
            ->join($nexopos_orders_products, $nexopos_orders_products.'.order_id', '=', $nexopos_orders.'.id')
            ->join($nexopos_products, $nexopos_products.'.id', '=', $nexopos_orders_products.'.product_id')
            ->join($nexopos_products_categories, $nexopos_products_categories.'.id', '=', $nexopos_products.'.category_id')
            ->select(
                $nexopos_orders.'.code as order_code',
                $nexopos_orders.'.total as order_total',
                $nexopos_orders.'.title as order_title',
                $nexopos_orders.'.payment_status as order_payment_status',
                $nexopos_orders.'.note as order_note',
                $nexopos_orders.'.note_visibility as order_note_visibility',
                $nexopos_orders.'.table_name as table_name',
                $nexopos_orders.'.area_name as area_name',
                $nexopos_orders.'.type as order_type',
                $nexopos_orders.'.id as order_id',
                $nexopos_orders.'.created_at as order_created_at',
                $nexopos_orders.'.updated_at as order_updated_at',
                $nexopos_orders.'.gastro_order_status as gastro_order_status',
            )
            ->groupBy([
                $nexopos_orders.'.id',
                $nexopos_orders.'.code',
                $nexopos_orders.'.total',
                $nexopos_orders.'.payment_status',
                $nexopos_orders.'.note',
                $nexopos_orders.'.note_visibility',
                $nexopos_orders.'.table_name',
                $nexopos_orders.'.area_name',
                $nexopos_orders.'.type',
                $nexopos_orders.'.id',
                $nexopos_orders.'.created_at',
                $nexopos_orders.'.updated_at',
                $nexopos_orders.'.gastro_order_status',
                $nexopos_orders.'.title',
            ]);

        /**
         * If the kitchen is set to support
         * various categories, we adjust the request
         * to filter order by products categories.
         */
        if (! empty($kitchenCategories)) {
            $request->whereIn($nexopos_products_categories.'.id', $kitchenCategories);
        }

        /**
         * will filter specific orders
         * if a cooking status filter is provided
         */
        if (isset($config['cooking_status'])) {
            $request->whereIn($nexopos_orders.'.gastro_order_status', $config['cooking_status']);
        }

        /**
         * if a time span is
         * provided, let's limit the results
         * accordingly.
         */
        if (isset($config['range_starts']) && isset($config['range_ends'])) {
            $request->where($nexopos_orders.'.created_at', '>=', $config['range_starts'])
                ->where($nexopos_orders.'.created_at', '<=', $config['range_ends']);
        }

        $request->orderBy($nexopos_orders.'.updated_at', 'desc');

        /**
         * we should retreive the order products with their modifiers
         */
        return $request->get()->map(function ($order) use ($config, $kitchenCategories) {
            $request = OrderProduct::where('order_id', $order->order_id)
                ->with('modifiers');

            /**
             * will filter specific meals
             * if a cooking status filter is provided
             */
            if (isset($config['cooking_status'])) {
                $request->whereIn('cooking_status', $config['cooking_status']);
            }

            /**
             * filter product by category if
             * a category is provided
             */
            if (! empty($kitchenCategories)) {
                $request->whereIn('product_category_id', $kitchenCategories);
            }

            $order->products = $request->get();

            return $order;
        });
    }

    /**
     * Will cook an order meal
     *
     * @param  Kitchen  $kitchen
     * @param  Order  $order
     * @param  array  $products
     * @return array
     */
    public function cookOrderMeal(Kitchen $kitchen, GastroOrder $order, $products)
    {
        // we might check if the order is assigned to the kitchen...
        // but that shouldn't be mandatory
        $products = $order->products->filter(fn ($product) => in_array($product->id, $products));

        if (($products->count() === 0)) {
            throw new NotAllowedException(__m('The meals send for cooking is not recognized as part of the order.', 'NsGastro'));
        }

        $products->each(function ($product) {
            $product->cooking_status = OrderProduct::COOKING_ONGOING;

            $this->depleteModifiersOnCooking($product);

            $product->save();
        });

        $order->refresh();

        KitchenAfterUpdatedOrderEvent::dispatch($order);

        return [
            'status'    =>  'success',
            'message'   =>  __m('The order meal is being cooked.', 'NsGastro'),
            'data'      =>  compact('order'),
        ];
    }

    /**
     * Consume product modifiers
     *
     * @param  Order  $order
     */
    public function depleteModifiersOnCooking(OrderProduct $product)
    {
        $product->modifiers->each(function ($modifier) {
            if ($modifier->product instanceof Product) {
                $unitQuantity = $modifier->product->unit_quantities->first();

                $this->productService->reduceUnitQuantities(
                    $modifier->product->id,
                    $unitQuantity->unit_id,
                    $modifier->quantity,
                    $unitQuantity->quantity
                );
            }
        });
    }

    public function getKitchenFromCategory($category_id)
    {
        $kitchenCategory = KitchenCategory::where('category_id', $category_id)->first();

        if ($kitchenCategory instanceof KitchenCategory) {
            return $kitchenCategory->kitchen;
        }

        return null;
    }
}
