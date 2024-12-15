<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiDataController;
use App\Http\Controllers\OrderDataController;
use App\Http\Controllers\StockDataController;
use App\Http\Controllers\DataController;

Route::post('/sales', [DataController::class, 'getSales']);
Route::get('/stock', [DataController::class, 'getStock']);
Route::get('/orders', [DataController::class, 'getOrders']);
Route::get('/datasales', [ApiDataController::class, 'index']);

Route::get('/dataorder', [OrderDataController::class, 'index']);

Route::get('/datastock', [StockDataController::class, 'index']);