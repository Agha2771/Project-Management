<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::prefix('tasks')->group(function () {
    Route::get('/{clientId}', action: [TaskController::class, 'index']);
    Route::get('', [TaskController::class, 'getTasks']);
    Route::post('/create', [TaskController::class, 'create']);
    Route::get('/delete/{projId}', [TaskController::class, 'destroy']);
    Route::put('/update/{projId}', [TaskController::class, 'update']);
    Route::post('/file-attachmets', [TaskController::class, 'storeAttachments']);
    Route::get('/remove-attachment/{attachmentid}', [TaskController::class, 'removeAttachment']);
});

