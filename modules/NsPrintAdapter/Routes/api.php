<?php

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Modules\NsPrintAdapter\Http\Controllers\NsPrintAdapterController;

Route::middleware([
    SubstituteBindings::class,
])->group(function () {
    Route::get('/ns-printadapter/sync-printers/{direction?}', [NsPrintAdapterController::class, 'syncPrinters'])->name(ns()->routeName('ns.print-adapter.sync-printers'));
    Route::get('/ns-printadapter/enabled-printers', [NsPrintAdapterController::class, 'getEnabledPrinters'])->name(ns()->routeName('ns.print-adapter.enabled-printers'));
    Route::get('/ns-printadapter/get-setups', [NsPrintAdapterController::class, 'getSetups'])->name(ns()->routeName('ns.print-adapter.get-setups'));
    Route::post('/ns-printadapter/print-data', [NsPrintAdapterController::class, 'getReceipt']);
    Route::post('/ns-printadapter/kitchen-print-data', [NsPrintAdapterController::class, 'getKitchenReceipt']);
    Route::post('/ns-printadapter/refresh-printers/', [NsPrintAdapterController::class, 'refreshPrinters'])->name(ns()->routeName('ns.print-adapter.refresh-printers'));
    Route::post('/ns-printadapter/save-settings', [NsPrintAdapterController::class, 'saveSettings'])->name(ns()->routeName('ns.print-adapter.save-printer-settings'));
    Route::post('/ns-printadapter/save-setup', [NsPrintAdapterController::class, 'saveSetup'])->name(ns()->routeName('ns.print-adapter.save-setup'));
    Route::post('/ns-printadapter/save-image', [NsPrintAdapterController::class, 'saveImage'])->name(ns()->routeName('ns.print-adapter.save-image'));
    Route::post('/ns-printadapter/cloud-print', [NsPrintAdapterController::class, 'printOnCloud'])->name(ns()->routeName('ns.print-adapter.cloud-print'));
    Route::get('/ns-printadapter/get-fields', [NsPrintAdapterController::class, 'getSettingsFields'])->name(ns()->routeName('ns.print-adapter.get-fields'));
    Route::post('/ns-printadapter/save-credentials', [NsPrintAdapterController::class, 'saveCredentials'])->name(ns()->routeName('ns.print-adapter.save-credentials'));
    Route::post('/ns-printadapter/test', [NsPrintAdapterController::class, 'testPrinter'])->name(ns()->routeName('ns.print-adapter.test-printer'));
});
