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


    Route::group(['middleware' => ['role:admin']], function () {

        Route::post('/product', [ProductController::class, 'store'])->name('product.store');

        Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');

        Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

});

Route::get('/product', [ProductController::class, 'index'])->name('product.index');

Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
