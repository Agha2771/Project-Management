<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/delete/{paymentId}', [PaymentController::class, 'destroy']);
    Route::post('/create', [PaymentController::class, 'create']);
});

