<?php

namespace Modules\NsGastro\Http\Controllers;

use App\Classes\Hook;
use App\Exceptions\NotAllowedException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Modules\NsGastro\Crud\AreaCrud;
use Modules\NsGastro\Crud\KitchensCrud;
use Modules\NsGastro\Crud\ModifierGroupCrud;
use Modules\NsGastro\Crud\ModifierGroupProductCrud;
use Modules\NsGastro\Crud\TableCrud;
use Modules\NsGastro\Events\NsGastroEvent;
use Modules\NsGastro\Models\Area;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\ModifierGroup;
use Modules\NsGastro\Models\Order as GastroOrder;
use Modules\NsGastro\Models\OrderProduct as GastroOrderProduct;
use Modules\NsGastro\Models\Table;
use Modules\NsGastro\Models\TableSession;
use Modules\NsGastro\Services\GastroOrderService;
use Modules\NsGastro\Services\KitchenService;
use Modules\NsGastro\Services\TableService;
use Modules\NsGastro\Settings\GastroSettings;

class RestaurantController extends Controller
{
    public function __construct(
        protected KitchenService $kitchenService,
        protected TableService $tableService,
        protected GastroOrderService $gastroOrderService,
        protected OrdersService $orderService
    ) {
        // ...
    }

    public function listAreas()
    {
        return AreaCrud::table();
    }

    public function createArea()
    {
        return AreaCrud::form();
    }

    public function editArea(Area $area)
    {
        return AreaCrud::form($area);
    }

    public function listTable()
    {
        return TableCrud::table();
    }

    public function createTable()
    {
        return TableCrud::form();
    }

    public function editTable(Table $table)
    {
        return TableCrud::form($table);
    }

    public function listKitchen()
    {
        return KitchensCrud::table();
    }

    public function createKitchen()
    {
        return KitchensCrud::form();
    }

    public function editKitchen(Kitchen $kitchen)
    {
        return KitchensCrud::form($kitchen);
    }

    public function listModifiersGroup()
    {
        return ModifierGroupCrud::table();
    }

    public function createModifiersGroup()
    {
        return ModifierGroupCrud::form();
    }

    public function editModifiersGroup(ModifierGroup $group)
    {
        return ModifierGroupCrud::form($group);
    }

    public function getModifierGroup(ModifierGroup $group)
    {
        $group->load(['modifiers.galleries', 'modifiers.unit_quantities']);

        return $group;
    }

    public function listModifiersGroupProducts( ModifierGroup $group )
    {
        return ModifierGroupProductCrud::table(
            title: sprintf( __m( '%s Products', 'NsGastro' ), $group->name ),
            queryParams: [
                'modifiers_group_id'    =>  $group->id,
            ]
        );
    }

    public function getTables(Request $request)
    {
        $tables = Table::status(Table::STATUS_AVAILABLE);

        /**
         * In case there is a filter ongoing
         * we'll return tables based on their status
         */
        if ($request->query('filter')) {
            switch ($request->query('filter')) {
                case 'free':
                    $tables->where('busy', false);
                break;
                case 'busy':
                    $tables->where('busy', true);
                break;
            }
        }

        /**
         * if the table assignation is enabled,
         * we need to pull tables that are assigned to the logged user
         */
        if (ns()->option->get('ns_gastro_tables_assignation_enabled', false)) {
            $tables->whereHas('assignedWaiters', function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        return $tables->orderBy('id')->get();
    }

    public function getAreas()
    {
        return Area::get();
    }

    public function getAreasAvailableTable(Area $area, Request $request)
    {
        if (! empty($request->query('filter'))) {
            return $area->tables()->busy($request->query('filter') === 'free' ? false : true)->get();
        }

        return $area->tables;
    }

    public function showKitchenScreen()
    {
        return View::make('NsGastro::kitchens.body', [
            'title' =>  __m('Kitchen Screen', 'NsGastro'),
        ]);
    }

    public function getAvailableKitchens()
    {
        return Kitchen::enabled()->get();
    }

    public function kitchenOrders(Kitchen $kitchen, Request $request)
    {
        return $this->kitchenService->getOrders($kitchen, $request->all());
    }

    public function cookOrderMeals(Kitchen $kitchen, GastroOrder $order, Request $request)
    {
        return $this->kitchenService->cookOrderMeal($kitchen, $order, $request->input('products'));
    }

    /**
     * Will proceed a meal cancelation for the
     * provided order.
     *
     * @param  Kitchen  $kitchen
     * @param  Order  $order
     * @param  Request  $request
     * @return array
     */
    public function cancelOrderMeals(Kitchen $kitchen, GastroOrder $order, Request $request)
    {
        return $this->gastroOrderService->cancelOrderMeals(
            $order,
            $request->input('products'),
            $request->input('reason'),
        );
    }

    /**
     * Will set a provided meals
     * as ready.
     *
     * @param  Kitchen  $kitchen
     * @param  GastroOrder  $order
     * @param  Request  $request
     * @return array
     */
    public function readyOrderMeals(Kitchen $kitchen, GastroOrder $order, Request $request)
    {
        return $this->gastroOrderService->readyOrderMeals(
            $order,
            $request->input('products')
        );
    }

    public function getTablesOrders(Table $table, Request $request)
    {
        return $this->tableService->getTableOrders($table, $request->input('range_starts'), $request->input('range_ends'));
    }

    public function getSessionOrders(Table $table, TableSession $session)
    {
        return $session->orders()->with('products.modifiers')->get();
    }

    public function getTablesSessions(Table $table, Request $request)
    {
        return $this->tableService->getTableSessions($table, $request->input('range_starts'), $request->input('range_ends'));
    }

    public function searchTables(Request $request)
    {
        return $this->tableService->searchTables(
            name: $request->input('search'), 
            ignore_table_id: $request->input( 'ignore_table_id' )
        );
    }

    public function cancelOrderProduct(GastroOrderProduct $product, Request $request)
    {
        return $this->gastroOrderService->cancelOrderMeals(
            $product->order,
            [ $product->id ],
            $request->input('reason')
        );
    }

    public function serveOrderProduct(GastroOrderProduct $product)
    {
        return $this->gastroOrderService->serveMeal($product);
    }

    public function serveAllMeals()
    {
        return $this->gastroOrderService->serveAllMeals();
    }

    public function serveOrderProductBulk( Request $request )
    {
        return $this->gastroOrderService->saveMealInBulk( $request->input( 'products' ) );
    }

    public function updateOrderProductNote(GastroOrderProduct $product, Request $request)
    {
        return $this->gastroOrderService->updateProductNote($product, $request->input('note'));
    }

    public function countReadyMeals()
    {
        return [
            'readyMeals'   =>  ns()->option->get('gastro_ready_meals', 0),
        ];
    }

    public function getReadyMeals()
    {
        $nexopos_orders = Hook::filter('ns-table-name', 'nexopos_orders');
        $nexopos_orders_products = Hook::filter('ns-table-name', 'nexopos_orders_products');

        return GastroOrderProduct::cookingStatus(GastroOrderProduct::COOKING_READY)
            ->with('order')
            ->select($nexopos_orders_products.'.*')
            ->join($nexopos_orders, $nexopos_orders.'.id', '=', 'order_id')
            ->whereIn($nexopos_orders.'.type', [
                GastroOrder::TYPE_DINEIN,
                GastroOrder::TYPE_DELIVERY,
                GastroOrder::TYPE_TAKEAWAY
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }

    public function addProducts(GastroOrder $order, Request $request)
    {
        return $this->gastroOrderService->addProducts(
            $order,
            $request->input('products')
        );
    }

    public function searchOrders(Request $request)
    {
        return GastroOrder::where('code', 'like', "%{$request->query('search')}%")
            ->limit(10)
            ->with(['customer'])
            ->paymentStatusIn([
                GastroOrder::PAYMENT_UNPAID,
                GastroOrder::PAYMENT_HOLD,
                GastroOrder::PAYMENT_PARTIALLY,
            ])
            ->get();
    }

    public function updateOrderSessions(Request $request, Table $table, TableSession $session)
    {
        switch ($request->input('action')) {
            case 'close':
                return $this->tableService->closeTableSession($session);
            case 'open':
                return $this->tableService->openTableSession($session);
            default:
                throw new NotAllowedException(__('Unexpected action provided.'));
        }
    }

    public function getOrderProducts($order_id)
    {
        return GastroOrderProduct::where('order_id', $order_id)
            ->with('modifiers')
            ->get();
    }

    public function getOrderTypes()
    {
        return [
            'types' =>  config('nexopos.orders.types'),
        ];
    }

    public function mergeOrders(Request $request)
    {
        return $this->gastroOrderService->mergeOrders(
            $request->input('orders'),
            $request->input('fields')
        );
    }

    public function splitOrders(Request $request)
    {
        return $this->gastroOrderService->splitOrders($request->all());
    }

    public function getSettings(Request $request)
    {
        return GastroSettings::renderForm([
            'title'     =>  __m('Gastro Settings', 'NsGastro'),
        ]);
    }

    public function getKitchenReceipts(Request $request, GastroOrder $order)
    {
        return $this->gastroOrderService->getKitchensProducts($order);
    }

    public function getKitchenCanceledReceipts(Request $request, GastroOrder $order)
    {
        return $this->gastroOrderService->getKitchensCanceledReceipts($order);
    }

    public function getKitchenReceiptForProducts(Request $request, GastroOrder $order)
    {
        return $this->gastroOrderService->getKitchensProducts($order, $request->input('products_id'));
    }

    public function changeOrderTable(Order $order, Request $request)
    {
        return $this->tableService->changeTable($order, $request->input('table_id'));
    }

    public function changeTableAvailability(Table $table, Request $request)
    {
        return $this->tableService->changeTableAvailability($table, $request->input('status'));
    }

    public function requestOrder(Order $order)
    {
        return $this->gastroOrderService->changeOrderCookingStatus($order, 'requested');
    }

    public function changeOrderCookingStatus(Order $order, Request $request)
    {
        return $this->gastroOrderService->changeOrderCookingStatus($order, $request->input('status'));
    }

    public function countRequestedOrders()
    {
        return [
            'count' => Order::where('gastro_order_status', GastroOrder::COOKING_REQUESTED)->count(),
        ];
    }
}
