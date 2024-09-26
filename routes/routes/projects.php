<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;


Route::prefix('projects')->group(function () {
    Route::get('/{clientId}', action: [ProjectController::class, 'index']);
    Route::get('', [ProjectController::class, 'getProjects']);
    Route::post('/create', [ProjectController::class, 'create']);
    Route::get('/delete/{projId}', [ProjectController::class, 'destroy']);
    Route::post('/file-attachmets', [ProjectController::class, 'storeAttachments']);
    Route::put('/update/{projId}', [ProjectController::class, 'update']);
    Route::get('/remove-attachment/{attachmentid}', [ProjectController::class, 'removeAttachment']);
});

