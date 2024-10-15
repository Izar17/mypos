<?php

use App\Http\Controllers\Dashboard\ManagerAuthController;
use Illuminate\Support\Facades\Route;

Route::post( '/manager_auth/sign-in', [ ManagerAuthController::class, 'postSignIn' ])->name( "app_auth.post" );