<?php

use Illuminate\Support\Facades\Route;
use Modules\NsGastro\Crud\CanceledProductCrud;
use Modules\NsGastro\Http\Controllers\RestaurantController;

Route::get('/restaurant/areas', [RestaurantController::class, 'listAreas'])->name(ns()->routeName('ns-gastro-areas'));
Route::get('/restaurant/areas/create', [RestaurantController::class, 'createArea'])->name(ns()->routeName('ns-gastro-areas-create'));
Route::get('/restaurant/areas/edit/{area}', [RestaurantController::class, 'editArea'])->name(ns()->routeName('ns-gastro-areas-edit'));

Route::get('/restaurant/modifiers-groups', [RestaurantController::class, 'listModifiersGroup'])->name(ns()->routeName('ns-gastro-modifiers-group'));
Route::get('/restaurant/modifiers-groups/create', [RestaurantController::class, 'createModifiersGroup'])->name(ns()->routeName('ns-gastro-modifiers-group-create'));
Route::get('/restaurant/modifiers-groups/edit/{group}', [RestaurantController::class, 'editModifiersGroup'])->name(ns()->routeName('ns-gastro-modifiers-group-edit'));
Route::get('/restaurant/modifiers-groups/{group}/modifiers', [RestaurantController::class, 'listModifiersGroupProducts'])->name(ns()->routeName('ns-gastro-modifiers-group-products'));
// Route::get( '/restaurant/modifiers-groups/explore/{modifier}/modifiers', [ RestaurantController::class, 'showModifiersGroupsModifiers' ]);

Route::get('/restaurant/tables', [RestaurantController::class, 'listTable'])->name(ns()->routeName('ns-gastro-tables'));
Route::get('/restaurant/tables/create', [RestaurantController::class, 'createTable'])->name(ns()->routeName('ns-gastro-tables-create'));
Route::get('/restaurant/tables/edit/{table}', [RestaurantController::class, 'editTable'])->name(ns()->routeName('ns-gastro-tables-edit'));

Route::get('/restaurant/kitchens', [RestaurantController::class, 'listKitchen'])->name(ns()->routeName('ns-gastro-kitchen-list'));
Route::get('/restaurant/kitchens/create', [RestaurantController::class, 'createKitchen'])->name(ns()->routeName('ns-gastro-kitchen-create'));
Route::get('/restaurant/kitchens/screen', [RestaurantController::class, 'showKitchenScreen'])->name(ns()->routeName('ns-gastro-kitchen-screen'));
Route::get('/restaurant/kitchens/edit/{kitchen}', [RestaurantController::class, 'editKitchen'])->name(ns()->routeName('ns-gastro-kitchen-edit'));
Route::get('/restaurant/settings', [RestaurantController::class, 'getSettings'])->name(ns()->routeName('ns-gastro-settings'));

Route::get('/restaurant/canceled-products', [ CanceledProductCrud::class, 'table' ])->name(ns()->routeName( 'ns-gastro-canceled-meals' ) );

