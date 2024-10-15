<?php

use App\Http\Middleware\NsRestrictMiddleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Modules\NsGastro\Http\Controllers\RestaurantController;

Route::middleware([
    SubstituteBindings::class,
])->group(function () {
    Route::get('/gastro/modifiers-groups/{group}', [RestaurantController::class, 'getModifierGroup']);
    Route::get('/gastro/order-types', [RestaurantController::class, 'getOrderTypes']);
    Route::get('/gastro/tables', [RestaurantController::class, 'getTables']);
    Route::post('/gastro/tables/search', [RestaurantController::class, 'searchTables']);
    Route::post('/gastro/tables/{table}/orders', [RestaurantController::class, 'getTablesOrders']);
    Route::post('/gastro/tables/{table}/sessions', [RestaurantController::class, 'getTablesSessions']);
    Route::post('/gastro/tables/{table}/sessions/{session}/orders', [RestaurantController::class, 'getSessionOrders']);
    Route::put('/gastro/tables/{table}/sessions/{session}/action', [RestaurantController::class, 'updateOrderSessions']);
    Route::post('/gastro/tables/{table}/change-availability', [RestaurantController::class, 'changeTableAvailability']);
    Route::post('/gastro/orders/{order}/add-products', [RestaurantController::class, 'addProducts']);
    Route::get('/gastro/orders/{order}/products', [RestaurantController::class, 'getOrderProducts']);
    Route::get('/gastro/orders/{order}/request', [RestaurantController::class, 'requestOrder']);
    Route::post('/gastro/orders/{order}/cooking-status', [RestaurantController::class, 'changeOrderCookingStatus']);
    Route::get('/gastro/orders/{order}/kitchen-receipts', [RestaurantController::class, 'getKitchenReceipts']);
    Route::get('/gastro/orders/{order}/kitchen-canceled-receipts', [RestaurantController::class, 'getKitchenCanceledReceipts']);
    Route::post('/gastro/orders/{order}/kitchen-receipts', [RestaurantController::class, 'getKitchenReceiptForProducts']);
    Route::post('/gastro/orders/{order}/change-table', [RestaurantController::class, 'changeOrderTable']);
    Route::get('/gastro/orders/search', [RestaurantController::class, 'searchOrders']);
    Route::post('/gastro/orders/split', [RestaurantController::class, 'splitOrders']);
    Route::post('/gastro/orders/merge', [RestaurantController::class, 'mergeOrders']);
    Route::post('/gastro/products/{product}/cancel', [RestaurantController::class, 'cancelOrderProduct'])->middleware( NsRestrictMiddleware::arguments( 'gastro.cancel.meals') );
    Route::post('/gastro/products/{product}/serve', [RestaurantController::class, 'serveOrderProduct'])->middleware( NsRestrictMiddleware::arguments( 'gastro.serve.meals') );
    Route::get('/gastro/products/serve-all', [RestaurantController::class, 'serveAllMeals'])->middleware( NsRestrictMiddleware::arguments( 'gastro.serve.meals') );
    Route::post('/gastro/products/serve', [RestaurantController::class, 'serveOrderProductBulk'])->middleware( NsRestrictMiddleware::arguments( 'gastro.serve.meals') );
    Route::post('/gastro/products/{product}/note', [RestaurantController::class, 'updateOrderProductNote'])->middleware( NsRestrictMiddleware::arguments( 'gastro.update.meals-note') );
    Route::get('/gastro/products/count-ready', [RestaurantController::class, 'countReadyMeals'])->middleware( NsRestrictMiddleware::arguments( 'gastro.update.meals-note') );
    Route::get('/gastro/products/ready', [RestaurantController::class, 'getReadyMeals'])->middleware( NsRestrictMiddleware::arguments( 'gastro.update.meals-note') );
    Route::get('/gastro/available-kitchens', [RestaurantController::class, 'getAvailableKitchens']);
    Route::get('/gastro/areas', [RestaurantController::class, 'getAreas']);
    Route::get('/gastro/areas/{area}/available-tables', [RestaurantController::class, 'getAreasAvailableTable']);
    // ? necessary ?
    Route::post('/gastro/kitchens/{kitchen}/orders/requested', [RestaurantController::class, 'getRequestedOrders']);
    Route::get('/gastro/kitchens/{kitchen}/orders/count-requested', [RestaurantController::class, 'countRequestedOrders']);
    Route::post('/gastro/kitchens/{kitchen}/orders', [RestaurantController::class, 'kitchenOrders'])->middleware( NsRestrictMiddleware::arguments( 'gastro.use.kitchens') );
    Route::post('/gastro/kitchens/{kitchen}/cook/{order}', [RestaurantController::class, 'cookOrderMeals'])->middleware( NsRestrictMiddleware::arguments( 'gastro.use.kitchens') );
    Route::post('/gastro/kitchens/{kitchen}/cancel/{order}', [RestaurantController::class, 'cancelOrderMeals'])->middleware( NsRestrictMiddleware::arguments( 'gastro.cancel.meals') );
    Route::post('/gastro/kitchens/{kitchen}/ready/{order}', [RestaurantController::class, 'readyOrderMeals'])->middleware( NsRestrictMiddleware::arguments( 'gastro.use.kitchens') );
});
