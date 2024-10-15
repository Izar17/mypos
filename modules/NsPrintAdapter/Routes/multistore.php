<?php

use Illuminate\Support\Facades\Route;
use Modules\NsPrintAdapter\Http\Controllers\NsPrintAdapterController;

Route::get('/print-adapter/settings', [NsPrintAdapterController::class, 'getSettingsPage'])->name(ns()->routeName('ns.print-adapter.settings'));
Route::get('/print-adapter/printers', [NsPrintAdapterController::class, 'getPrinters'])->name(ns()->routeName('ns.print-adapter.printers'));
Route::get('/print-adapter/printers/create', [NsPrintAdapterController::class, 'createPrinter'])->name(ns()->routeName('ns.print-adapter.create-printer'));
Route::get('/print-adapter/printers/edit/{printer}', [NsPrintAdapterController::class, 'editPrinter'])->name(ns()->routeName('ns.print-adapter.edit-printer'));
Route::get('/print-adapter/authenticate', [NsPrintAdapterController::class, 'getAuthentication'])->name(ns()->routeName('ns.print-adapter.authenticate'));
Route::get('/print-adapter/unlink', [NsPrintAdapterController::class, 'unlink'])->name(ns()->routeName('ns.print-adapter.unlink'));
Route::get('/print-adapter/delete-setup', [NsPrintAdapterController::class, 'deleteSetup'])->name(ns()->routeName('ns.print-adapter.delete-setup'));
Route::get('/nspa/callback', [NsPrintAdapterController::class, 'authenticateCallback'])->name(ns()->routeName('ns.print-adapter.authenticate-callback'));
