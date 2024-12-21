<?php

namespace Modules\NsGastro\Services;

use App\Classes\Hook;
use App\Events\OrderAfterUpdatedEvent;
use App\Exceptions\NotAllowedException;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductUnitQuantity;
use App\Models\Role;
use App\Models\Unit;
use App\Services\NotificationService;
use App\Services\OrdersService;
use App\Services\ProductCategoryService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Modules\NsGastro\Events\GastroAfterCanceledOrderProductEvent;
use Modules\NsGastro\Events\GastroBeforeCanceledOrderProductEvent;
use Modules\NsGastro\Events\GastroNewProductAddedToOrderEvent;
use Modules\NsGastro\Events\GastroOrderAfterMergeEvent;
use Modules\NsGastro\Events\KitchenAfterUpdatedOrderEvent;
use Modules\NsGastro\Events\TableAfterUpdatedEvent;
use Modules\NsGastro\Models\Area;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\KitchenCategory;
use Modules\NsGastro\Models\KitchenPrinter;
use Modules\NsGastro\Models\ModifierGroup;
use Modules\NsGastro\Models\Order as GastroOrder;
use Modules\NsGastro\Models\OrderProduct as GastroOrderProduct;
use Modules\NsGastro\Models\OrderProductModifier;
use Modules\NsGastro\Models\OrderProductModifierGroup;
use Modules\NsGastro\Models\ProductModifierGroup;
use Modules\NsGastro\Models\Table;
use Modules\NsPrintAdapter\Services\PrintService;

class GastroOrderService
{
    public function __construct(
        private OrdersService $ordersService,
        private ProductCategoryService $productCategoryService,
        private TableService $tableService,
        private NotificationService $notificationService
    ) {
        // ...
    }

    /**
     * Will cancel a meal attached to an order
     *
     * @param  Kitchen  $kitchen
     * @param  Order  $order
     * @param  array  $products
     * @return array
     */
    public function cancelOrderMeals(GastroOrder $order, $products_id, $reason)
    {
        $products = $order->products->filter(fn ($product) => in_array($product->id, $products_id));

        if (($products->count() === 0)) {
            throw new NotAllowedException(__m('The meals send for cancelation is not recognized as part of the order.', 'NsGastro'));
        }

        $products->each(function ($product) use ($reason) {
            $this->cancelSingleItem( $product, $reason );
        });

        $this->ordersService->refreshOrder( $order );

        return [
            'status'    =>  'success',
            'message'   =>  __m('The order meal has been canceled.', 'NsGastro'),
            'data'      =>  compact('order'),
        ];
    }

    public function countReadyMeals()
    {
        $nexopos_orders = Hook::filter('ns-table-name', 'nexopos_orders');
        $nexopos_orders_products = Hook::filter('ns-table-name', 'nexopos_orders_products');

        $readyMeals = GastroOrderProduct::cookingStatus(GastroOrderProduct::COOKING_READY)
            ->join($nexopos_orders, $nexopos_orders.'.id', '=', $nexopos_orders_products.'.order_id')
            ->whereIn($nexopos_orders.'.type', [GastroOrder::TYPE_DINEIN])
            ->orderBy('updated_at', 'desc')
            ->count();

        ns()->option->set('gastro_ready_meals', $readyMeals);
    }

    public function checkOrderCookingStatus( Order $order )
    {
        $totalProducts = $order->products()->count();
        $totalPending = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_PENDING)->count();
        $totalOngoing = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_ONGOING)->count();
        $totalReady = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_READY)->count();
        $totalRequested = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_REQUESTED)->count();
        $totalServed = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_SERVED)->count();
        $totalCanceled = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_CANCELED)->count();
        $totalProcessed = $order->products()->where('cooking_status', GastroOrderProduct::COOKING_PROCESSED)->count();

        /**
         * When all the order should be marked as pending
         */
        if ($totalPending === $totalProducts - $totalCanceled) {
            $order->gastro_order_status = GastroOrder::COOKING_PENDING;
        }

        /**
         * When the order should be marked as ongoing
         */
        if ($totalOngoing > 0) {
            $order->gastro_order_status = GastroOrder::COOKING_ONGOING;
        }

        /**
         * When the order should be marked as ready.
         */
        if ($totalReady === ($totalProducts - $totalCanceled)) {
            $order->gastro_order_status = GastroOrder::COOKING_READY;
        }

        /**
         * When the order should be marked as served.
         */
        if ($totalRequested === ($totalProducts - $totalCanceled)) {
            $order->gastro_order_status = GastroOrder::COOKING_REQUESTED;
        }

        /**
         * When the order should be marked as served.
         */
        if ($totalServed === ($totalProducts - $totalCanceled)) {
            $order->gastro_order_status = GastroOrder::COOKING_SERVED;
        }

        /**
         * The verification is performed
         * let's update the order
         */
        $order->saveQuietly();
    }

    public function cancelSingleItem( $product, $reason = '' )
    {
        GastroBeforeCanceledOrderProductEvent::dispatch($product);

        $product->cooking_status = GastroOrderProduct::COOKING_CANCELED;
        $product->cooking_cancelation_note = $reason;
        $product->meal_canceled_by = Auth::id();
        $product->meal_canceled_by_name = Auth::user()->username;
        $product->meal_cancelation_quantity = $product->quantity;
        $product->quantity = 0;
        $product->discount = 0;
        $product->price_without_tax = 0;
        $product->tax_value = 0;
        $product->price_with_tax = 0;
        $product->total_price = 0;
        $product->total_purchase_price = 0;
        $product->total_price_with_tax = 0;
        $product->total_price_without_tax = 0;
        $product->save();

        /**
         * If a meal is canceled
         * we should be able to report it.
         */
        GastroAfterCanceledOrderProductEvent::dispatch($product);
    }

    /**
     * Will set order meals as ready
     *
     * @param  Order  $order
     * @param  array  $products
     * @return array
     */
    public function readyOrderMeals(GastroOrder $order, $products)
    {
        $products = $order->products->filter(fn ($product) => in_array($product->id, $products));

        if (($products->count() === 0)) {
            throw new NotAllowedException(__m('The meals send for cancelation is not recognized as part of the order.', 'NsGastro'));
        }

        $products->each(function ($product) {
            $product->cooking_status = GastroOrderProduct::COOKING_READY;
            $product->save();
        });

        $this->ordersService->refreshOrder( $order );

        return [
            'status'    =>  'success',
            'message'   =>  __m('The order meals has been set as ready.', 'NsGastro'),
            'data'      =>  compact('order'),
        ];
    }

    /**
     * Will change the status of a product from
     * served to cooked.
     *
     * @param  GastroOrderProduct  $product
     * @return void
     */
    public function serveMeal(GastroOrderProduct $product)
    {
        if ($product->cooking_status !== 'ready') {
            throw new NotAllowedException(__m('Unable to serve a meal that is not ready.', 'NsGastro'));
        }

        $product->cooking_status = GastroOrderProduct::COOKING_SERVED;
        $product->meal_served_by = Auth::id();
        $product->meal_served_by_name = Auth::user()->username;
        $product->save();

        $this->ordersService->refreshOrder( $product->order );

        return [
            'status'    =>  'success',
            'message'   =>  __m('The meal has been correctly served.', 'NsGastro'),
        ];
    }

    public function serveAllMeals()
    {
        GastroOrderProduct::where( 'cooking_status', GastroOrderProduct::COOKING_READY )->update([
            'cooking_status' => GastroOrderProduct::COOKING_SERVED
        ]);

        return [
            'status'    =>  'success',
            'message'   =>  __m( 'The cooking status of the products has been updated.', 'NsGastro' )
        ];
    }

    /**
     * Will change the status of a product from
     * served to cooked.
     *
     * @param  GastroOrderProduct  $product
     * @return void
     */
    public function saveMealInBulk( $products_id )
    {
        $result     =   [
            'success'   =>  0,
            'failure'   =>  0,
        ];

        foreach( $products_id as $id ) {
            $product    =   GastroOrderProduct::find( $id );
            try {
                $this->serveMeal( $product );
                $result[ 'success' ]++;
            } catch( Exception $exception ) {
                $result[ 'failure' ]++;
            }
        }

        return [
            'status'    =>  'success',
            'message'   =>  sprintf( 
                __m('%s meal(s) were marked as served, %s failed.', 'NsGastro'), 
                $result[ 'success' ],
                $result[ 'failure' ]
            )
        ];
    }

    /**
     * Will update the order note
     *
     * @param  GastroOrderProduct  $product
     * @param  string  $note
     * @return array response
     */
    public function updateProductNote(GastroOrderProduct $product, $note)
    {
        $product->cooking_note = $note;
        $product->save();

        return [
            'status'    =>  'success',
            'message'   =>  __m('The Order Product has been successfully updated.', 'NsGastro'),
        ];
    }

    /**
     * Add products to an already placed order
     *
     * @param  Order  $order
     * @param  array  $products
     * @return array
     */
    public function addProducts(Order $order, $products)
    {
        foreach ($products as &$product) {
            $product['product'] = Product::find($product['product_id']);
            $product['unitQuantity'] = ProductUnitQuantity::find($product['unit_quantity_id']);
            $product['unit_id'] = $product['unitQuantity']->unit_id;
        }

        $response = $this->ordersService->addProducts($order, $products);

        GastroNewProductAddedToOrderEvent::dispatch( $order, $products );

        return $response;
    }

    /**
     * Split on order in mutiple slices.
     *
     * @param  array  $data
     * @return array $result
     */
    public function splitOrders($data)
    {
        /**
         * fetch original order
         */
        $order = Order::findOrFail($data['original']['id']);

        $result = collect($data['slices'])->map(function ($slice) use ($order) {
            $table = false;

            if (isset($slice['table_id'])) {
                $table = Table::find($slice['table_id']);
                $table->selected = true; // while saving the order, gastro needs this to consider the table and eventually starts the session.
            }

            $products = collect($slice['products'])->map(function ($product) {
                unset($product['id']);

                /**
                 * recreate modifiers groups
                 * if it's provided
                 */
                if (! empty($product['modifiers'])) {
                    $groups = [];

                    collect($product['modifiers'])->each(function ($modifier) use (&$groups) {
                        $freshModifier = Product::with('unit_quantities')->find($modifier['modifier_id']);
                        $group = ModifierGroup::find($freshModifier->modifiers_group_id);

                        /**
                         * because by default for modifiers
                         * we always pic the first unit.
                         */
                        $modifier['unit_quantities'] = $freshModifier->unit_quantities;
                        $modifier['stock_management'] = $freshModifier->stock_management;
                        $groups[$group->id][] = $modifier;
                    });

                    $product['modifiersGroups'] = collect($groups)->map(function ($modifiers, $group_id) {
                        $group = ModifierGroup::find($group_id);
                        $group->modifier_group_id = $group->id;
                        $group->modifiers = $modifiers;

                        $finalGroup = $group->toArray();
                        unset($finalGroup['id']);

                        return $finalGroup;
                    })->toArray();
                }

                return $product;
            });

            $orderData = [
                'customer_id'           =>  $slice['customer_id'],
                'type'                  =>  ['identifier' => $slice['type']],
                'title'                 =>  $slice['name'] ?? '',
                'gastro_order_status'   =>  GastroOrder::COOKING_PENDING,
                'note_visibility'       =>  'hidden',
                'table_id'              =>  $slice['table_id'] ?? 0,
                'table'                 =>  $table,
                'table_name'            =>  $table ? $table->name : '',
                'seats'                 =>  $table ? $table->seats : 0,
                'products'              =>  $products,
                'payment_status'        =>  Order::PAYMENT_HOLD,
                'area_id'               =>  $table && $table->area ? $table->area->id : 0,
                'area_name'             =>  $table && $table->area ? $table->area->name : '',
            ];

            $result = $this->ordersService->create($orderData);
            
            $order = $result['data']['order'];
            
            /**
             * we'll check if the order is assigned to a table
             * by retreiving the table model. For a performance matter
             * we'll first check if the table_id is not empty.
             */
            if ( ! empty( $order->table_id ) ) {
                $table = Table::find($order->table_id);
    
                if ( $table instanceof Table ) {
                    $this->tableService->startTableSession(
                        table: $table, 
                        silent: true 
                    );
                }
            }

            return $result;
        });

        $this->ordersService->deleteOrder($order);

        return  [
            'status'    =>  'success',
            'message'   =>  __m('The order has been splitted.', 'NsGastro'),
            'data'      =>  $result,
        ];
    }

    /*
     * Will merge 2 or mores order within
     * Gastro, by deleting previous reference.
     * @param array $orders
     * @return array
     */
    public function mergeOrders( array $orders, array $details)
    {
        $isolatedProducts = collect([]);

        foreach ($orders as $order) {
            // we'll check if the order is not yet paid
            if ($order['payment_status'] === GastroOrder::PAYMENT_PAID) {
                throw new NotAllowedException(__m('Unable to merge an already paid order.', 'NsGastro'));
            }

            $isolatedProducts->push(
                $this->ordersService->getOrderProducts($order['id'])
            );
        }

        /**
         * We'll change the order reference for
         * each of these product and trigger a refresh
         */
        $products = $isolatedProducts
            ->flatten()
            ->map( function( $product ) {
                $productData    =   $product->toArray();
                $productData[ 'modifiersGroups' ]   =   [];

                $groups  =   OrderProductModifierGroup::where( 'order_product_id', $product->id )->with( 'modifiers' )->get();
                $groups->map( function( $group ) use ( &$productData ) {
                    unset( $group[ 'id' ] );
                    
                    $group[ 'modifiers' ]   =   $group->modifiers->map( function( $modifier ) {
                        unset( $modifier[ 'id' ] );
                        return $modifier;
                    });

                    $productData[ 'modifiersGroups' ][] = $group->toArray();
                });
                
                return $productData;
            });

        $table = Table::with( 'area' )->find($details['table_id'] ?? null);
        $table->selected    =   true; // gastro needs this to be defined, in order to consider and eventually start the table session.

        $orderData = [
            'title'             =>  $details['name'] ?? __m( 'Merged Order', 'NsGastro' ),
            'customer_id'       =>  $details['customer_id'],
            'type'              =>  ['identifier' => $details['type']],
            'table_id'          =>  $details['table_id'] ?? null,
            'payment_status'    =>  Order::PAYMENT_HOLD,
            'table'             =>  $table,
            'seats'             =>  $table instanceof Table ? ($table->seats ?: 0) : 0,
            'table_name'        =>  $table instanceof Table ? $table->name : '',
            'products'          =>  $products->toArray(),
            'area_id'           =>  $table->area instanceof Area ? ($table->area->id) : 0,
            'area_name'         =>  $table->area instanceof Area ? ($table->area->name) : 0,
        ];

        $result = $this->ordersService->create($orderData);

        $this->tableService->startTableSessionUsingOrder( $result[ 'data' ][ 'order' ] );

        /**
         * We'll delete previous orders
         */
        collect($orders)->each(function ($order) {
            $order = Order::find($order['id']);

            if ($order instanceof Order) {
                $this->ordersService->deleteOrder($order);
            }
        });

        GastroOrderAfterMergeEvent::dispatch( $orders, $result[ 'data' ][ 'order' ] );

        return [
            'status'    =>  'success',
            'message'   =>  __m('The orders successfully merged.', 'NsGastro'),
            'data'      =>  $result['data'],
        ];
    }

    /**
     * Will extract the product to print
     * per kitchen according to the assigned categories.
     * It doesn't mark the products as printed.
     */
    public function getKitchensProducts( GastroOrder $order, array $products_id = []): Collection
    {
        $products = $order->products()
            ->with( 'modifiers' )
            ->where('meal_printed', false)
            ->get();

        /**
         * This likely means that there is
         * no products that needs to be printed.
         */
        if ($products->count() === 0) {
            return collect([]);
        }

        $productCategories = $products
            ->filter(fn ($orderProduct) => empty($products_id) || in_array($orderProduct->id, $products_id))
            ->filter(fn ($orderProduct) => $orderProduct->product_category_id > 0)
            ->map(fn ($orderProduct) => $orderProduct->product_category_id )
            ->unique();

        /**
         * This will ensures to retreive all
         * parent category from the products
         *
         * @todo Unit Test Needed
         */

        $categories = collect($productCategories)->map(function ($category_id) {
            return $this->productCategoryService->getCategoryParents($category_id);
        })->flatten();

        /**
         * Let's merge the product category to make
         * sure it remains for checking the kitchens
         */
        $categories->merge($productCategories->toArray());

        $kitchens = KitchenCategory::whereIn('category_id', $categories)
            ->with('kitchen')
            ->select('kitchen_id')
            ->groupBy(['kitchen_id'])
            ->get();

        return $kitchens->map(function ( $kitchenCategory ) use ( $products, $order ) {
            if ($kitchenCategory->kitchen !== null) {
                $categories = $kitchenCategory->kitchen->categories()
                    ->get('category_id')
                    ->pluck('category_id')
                    ->toArray();
            } else {
                $categories = [];
            }

            $childrens = collect($categories)->map(function ($category) {
                return $this->productCategoryService->getCategoryChildrens($category);
            })->flatten();

            $categories = array_merge($childrens->toArray(), $categories);

            $products = $products
                ->filter(
                    fn ($orderProduct) => in_array($orderProduct->product_category_id, $categories)
                )
                ->map(function ($product) {
                    $product->meal_printed = true;
                    $product->save();

                    return $product;
                });

            $kitchen            =   $kitchenCategory->kitchen;
            $kitchenPrinters    =   KitchenPrinter::with( 'printer' )->where('kitchen_id', $kitchenCategory->kitchen_id)->get();

            return [
                'kitchen'       =>  $kitchen,
                'products'      =>  $products,
                'order'         =>  $order,
                'reference_id'  =>  $order->id,
                'printers'      =>  $kitchenPrinters->map( fn( $kitchenPrinter ) => $kitchenPrinter->printer )
            ];
        });
    }

    public function getKitchensCanceledReceipts( GastroOrder $order )
    {
        $products = $order->products()
            ->where( 'cooking_status', GastroOrderProduct::COOKING_CANCELED )
            // ->where( 'meal_cancelation_printed', false )
            ->get();

        /**
         * This likely means that there is
         * no products that needs to be printed.
         */
        if ($products->count() === 0) {
            return collect([]);
        }

        $productCategories = $products
            ->filter(fn ($orderProduct) => empty($products_id) || in_array($orderProduct->id, $products_id))
            ->filter(fn ($orderProduct) => $orderProduct->product_category_id > 0)
            ->map(fn ($orderProduct) => $orderProduct->product_category_id)
            ->unique();

        /**
         * This will ensures to retreive all
         * parent category from the products
         *
         * @todo Unit Test Needed
         */
        $categories = collect($productCategories)->map(function ($category_id) {
            return $this->productCategoryService->getCategoryParents($category_id);
        })->flatten();

        /**
         * Let's merge the product category to make
         * sure it remains for checking the kitchens
         */
        $categories->merge($productCategories->toArray());

        $kitchens = KitchenCategory::whereIn('category_id', $categories)
            ->with('kitchen')
            ->select('kitchen_id')
            ->groupBy(['kitchen_id'])
            ->get();

        $receipts = $kitchens->map(function ($kitchenCategory) use ($order, $products ) {
            if ($kitchenCategory->kitchen !== null) {
                $categories = $kitchenCategory->kitchen->categories()
                    ->get('category_id')
                    ->pluck('category_id')
                    ->toArray();
            } else {
                $categories = [];
            }

            $childrens = collect($categories)->map(function ($category) {
                return $this->productCategoryService->getCategoryChildrens($category);
            })->flatten();

            $categories = array_merge($childrens->toArray(), $categories);

            $products = $products
                ->filter(
                    fn ($orderProduct) => in_array($orderProduct->product_category_id, $categories)
                );

            $printService = new PrintService;
            $kitchen = $kitchenCategory->kitchen;

            /**
             * if we're returning the items, we
             * can mark the returned items as printed
             */
            $products->each(function ($product) {
                $product->meal_cancelation_printed = true;
                $product->save();
            });

            return [
                'kitchen'       =>  $kitchen,
                'products'      =>  $products,
                'nps_address'   =>  Str::finish(ns()->option->get('ns_pa_server_address'), '/'),
                'printers'      =>  KitchenPrinter::where('kitchen_id', $kitchenCategory->kitchen_id)->get()->map(fn ($printer) => $printer->printer),
            ];
        });

        return $receipts;
    }

    public function changeOrderCookingStatus(Order $order, $status)
    {
        $order->gastro_order_status = $status;
        $order->products->each(function ($product) use ($status) {
            $product->cooking_status = $status;
            $product->save();
        });

        $order->save();

        OrderAfterUpdatedEvent::dispatch($order);

        return [
            'status'    =>  'success',
            'message'   =>  __m('The order cooking status has been updated.', 'NsGastro'),
        ];
    }

    public function setOrderAsPending( Order $order )
    {
        $order->gastro_order_status = GastroOrder::COOKING_ONGOING;
        $order->save();
    }

    public function checkOrderDetails( Order | null $order, array $fields )
    {
        /**
         * @var TableService
         */
        $tableService = app()->make(TableService::class);

        /**
         * If the table doesn't
         * allow multiple clients
         */
        if (
            isset($fields['table']) &&
            ! empty($fields['table']) &&
            ! (bool) $fields['table']['allow_multi_clients'] &&
            (bool) ns()->option->get('ns_gastro_enable_table_sessions', false)) {
            $table = Table::find($fields['table']['id']);
            $orders = $tableService->getTableOrders($table);

            if ($orders->isNotEmpty()) {
                $customers_id = $orders->map(fn ($order) => $order->customer_id)->toArray();

                if (isset($fields['customer']) && ! in_array($fields['customer']['id'], $customers_id)) {
                    throw new NotAllowedException(sprintf(
                        __('The table is already busy with a different customer "%s". Assigning a new customer is disallowed by the "%s" settings.'),
                        $orders->first()->customer->name,
                        $table->name
                    ));
                }
            }
        }
    }

    public function populateOrderProductDetails( OrderProduct $orderProduct, array $data )
    {
        $orderProduct->modifiers_gross_total = $data['modifiers_gross_total'] ?? 0;
        $orderProduct->modifiers_total = $data['modifiers_total'] ?? 0;
        $orderProduct->modifiers_net_total = $data['modifiers_net_total'] ?? 0;
    }

    public function storeOrderProductModifiers( $order, $product, $data )
    {
        $storedGroups = [];
        $storedModifiers = [];

        if (isset($data['modifiersGroups'])) {
            foreach ($data['modifiersGroups'] as $rawGroup) {
                $group = OrderProductModifierGroup::find($rawGroup['id'] ?? null);

                if (! $group instanceof OrderProductModifierGroup) {
                    $group = new OrderProductModifierGroup;
                }

                $group->forced = $rawGroup['forced'];
                $group->multiselect = $rawGroup['multiselect'];
                $group->name = $rawGroup['name'];
                $group->order_product_id = $product->id;
                $group->modifier_group_id = $rawGroup['modifier_group_id'];
                $group->countable = $rawGroup['countable'];
                $group->save();

                foreach ($rawGroup['modifiers'] as $rawModifier) {
                    $modifier = OrderProductModifier::find($rawModifier['id'] ?? null);

                    if (! $modifier instanceof OrderProductModifier) {
                        $modifier = new OrderProductModifier;
                    }

                    $modifier->unit_price = $rawModifier['unit_price'];
                    $modifier->quantity = $rawModifier['quantity'];
                    $modifier->name = $rawModifier['name'];
                    $modifier->order_product_id = $product->id;
                    $modifier->unit_quantity_id = $rawModifier['unit_quantity_id'];
                    $modifier->unit_id = $rawModifier['unit_id'];
                    $modifier->modifier_id = $rawModifier['modifier_id'];
                    $modifier->order_product_modifier_group_id = $group->id;
                    $modifier->tax_value = 0;
                    $modifier->total_price = $rawModifier['total_price'];
                    $modifier->save();

                    $storedModifiers[] = $modifier->id;

                    // we should normaly deplete the material from here.
                }

                $storedGroups[] = $group->id;
            }
        }

        $product->cooking_status = $data['cooking_status'] ?? 'pending';
        $product->cooking_note = $data['cooking_note'] ?? '';
        $product->meal_placed_by = Auth::id();
        $product->meal_placed_by_name = Auth::user()->username;
        $product->saveQuietly();

        /**
         * delete ressource that has been ignored
         * from the POS (in case the order is edited)
         */
        OrderProductModifier::whereNotIn('id', $storedModifiers)
            ->where('order_product_id', $product->id)
            ->delete();

        OrderProductModifierGroup::whereNotIn('id', $storedGroups)
            ->where('order_product_id', $product->id)
            ->delete();
    }

    public function computeOrderProduct( $orderProduct )
    {
        /**
         * IF the discount defined is based on
         * a percentage, we need to compute the discount
         * for the modifier provided.
         */
        $modifiersDiscounts = 0;

        if ($orderProduct->discount_type === 'percentage') {
            /**
             * @var OrdersService
             */
            $ordersService = app()->make(OrdersService::class);
            $modifiersDiscounts = $ordersService->computeDiscountValues(
                $orderProduct->discount_percentage,
                $orderProduct->modifiers_total
            );
        }

        $orderProduct->total_price = ns()->currency
            ->fresh($orderProduct->unit_price)
            ->multiplyBy($orderProduct->quantity)
            ->additionateBy($orderProduct->modifiers_total)
            ->subtractBy($orderProduct->discount + $modifiersDiscounts)
            ->get();
    }

    public function checkInventory( $items, $session_id )
    {
        collect($items)->each(function ($orderProduct) use ($session_id) {
            if (isset($orderProduct['modifiersGroups'])) {
                foreach ($orderProduct['modifiersGroups'] as $group) {
                    foreach ($group['modifiers'] as $modifier) {
                        $product = (object) $modifier;
                        $product->id = $product->modifier_id;

                        /**
                         * the unit needs to be attached
                         * to the unit quantity
                         */
                        $unitQuantity = ProductUnitQuantity::find($modifier['unit_quantity_id']);
                        $unitQuantity->unit = Unit::findOrFail($unitQuantity->unit_id);

                        $modifier['unit_quantity_id'] = $unitQuantity->id;
                        $modifier['product_id'] = $modifier['modifier_id'];

                        try {
                            $originalProduct = Product::find($product->id);
                            $this->ordersService->checkQuantityAvailability(
                                $originalProduct,
                                $unitQuantity, // the modifier always use the first unit quantity
                                $modifier,  // as an OrderProduct
                                $session_id
                            );
                        } catch (Exception $exception) {
                            throw new Exception(
                                sprintf(
                                    __m('An error has occured with a modifier for the product "%s". Error : "%s"', 'NsGastro'),
                                    $orderProduct['name'],
                                    $exception->getMessage()
                                )
                            );
                        }
                    }
                }
            }
        });
    }

    public function deleteModifiers( $product )
    {
        ProductModifierGroup::where( 'product_id', $product->id )->delete();
    }
}
