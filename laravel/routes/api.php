<?php

use App\Http\Controllers\Api\DriverOrderController;

Route::get('/driver/orders/new', [DriverOrderController::class, 'latestNewOrder']);
Route::get('/driver/orders', [DriverOrderController::class, 'index']);          
Route::get('/driver/orders/{id}', [DriverOrderController::class, 'show']); 
Route::get('/driver/orders/{id}', [DriverOrderController::class, 'show']);

Route::post('/driver/orders/{id}/delivered', [DriverOrderController::class, 'markDelivered']);
Route::post('/driver/orders/{id}/not-delivered', [DriverOrderController::class, 'markNotDelivered']);

Route::post('/driver/orders/{id}/delivered', [DriverOrderController::class, 'markDelivered']);
Route::post('/driver/orders/{id}/not-delivered', [DriverOrderController::class, 'markNotDelivered']);