<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Modules\NsPrintAdapter\Http\Controllers\NsPrintAdapterController;

Route::prefix('dashboard')->group(function () {
    Route::middleware([
        SubstituteBindings::class,
        Authenticate::class,
    ])->group(function () {
        include_once dirname(__FILE__).'/multistore.php';
    });
});

Route::prefix( 'nps/images' )->group( function(){
    Route::get( 'sale-receipt/{reference_id}', [ NsPrintAdapterController::class, 'saleReceipt' ])->name( ns()->routeName( 'nps-image.sale-receipt' ) );
    Route::get( 'refund-receipt/{reference_id}', [ NsPrintAdapterController::class, 'refundReceipt' ])->name( ns()->routeName( 'nps-image.refund-receipt' ) );
    Route::get( 'kitchen-receipt/{reference_id}', [ NsPrintAdapterController::class, 'kitchenReceipt' ])->name( ns()->routeName( 'nps-image.kitchen-receipt' ) );
    Route::get( 'payment-receipt/{reference_id}', [ NsPrintAdapterController::class, 'paymentReceipt' ])->name( ns()->routeName( 'nps-image.payment-receipt' ) );
});
