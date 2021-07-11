<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\IntegrationController;

//Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);


Route::middleware('auth:api')->group( function () {
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::post('order/start-production', [IntegrationController::class, 'startProduction']);
    Route::post('order/cancel-production', [IntegrationController::class, 'cancelProduction']);
    Route::get('order/check/{order_id}', [IntegrationController::class, 'checkIfCreated']);
});