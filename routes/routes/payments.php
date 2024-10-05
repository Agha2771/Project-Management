<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/delete/{paymentId}', [PaymentController::class, 'destroy']);
    Route::post('/file-attachmets', [PaymentController::class, 'storeAttachments']);
    Route::post('/create', [PaymentController::class, 'create']);
    Route::put('/update/{paymentId}', [PaymentController::class, 'update']);
    Route::get('/remove-attachment/{attachmentid}', [PaymentController::class, 'removeAttachment']);
});

