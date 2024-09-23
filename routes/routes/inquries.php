<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;

Route::prefix('inquiries')->group(function () {
    Route::get('', [InquiryController::class, 'getLeads']);
    Route::get('/{clientId}', [InquiryController::class, 'index']);
    Route::post('/create', [InquiryController::class, 'create']);
    Route::get('/delete/{InquiryId}', [InquiryController::class, 'destroy']);
    Route::post('/attachmets', [InquiryController::class, 'storeAttachments']);
    Route::put('/update/{InquiryId}', [InquiryController::class, 'update']);
});

