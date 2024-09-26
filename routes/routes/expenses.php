<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;


Route::prefix('expenses')->group(function () {
    Route::get('', [ExpenseController::class, 'index']);
    Route::get('/delete/{exp}', [ExpenseController::class, 'destroy']);
    Route::post('/create', [ExpenseController::class, 'create']);
    Route::put('/update/{exp}', [ExpenseController::class, 'update']);
    Route::post('/file-attachmets', [ExpenseController::class, 'storeAttachments']);
    Route::get('/remove-attachment/{attachmentid}', [ExpenseController::class, 'removeAttachment']);
});

