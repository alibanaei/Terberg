<?php


use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\OrderController;
use \App\Http\Controllers\API\ProductController;
use \App\Http\Controllers\API\ServiceController;
use \App\Http\Controllers\API\OptionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::apiResource('order', OrderController::class);

    Route::apiResource('product', ProductController::class);

    Route::apiResource('service', ServiceController::class);

    Route::apiResource('option', OptionController::class);

});
