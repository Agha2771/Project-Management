<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;


Route::prefix('projects')->group(function () {
    Route::get('/delete/{projId}', [ProjectController::class, 'destroy']);
    Route::get('/{clientId}/{type?}', action: [ProjectController::class, 'index']);
    Route::get('', [ProjectController::class, 'getProjects']);
    Route::post('/create', [ProjectController::class, 'create']);
    Route::put('/update/{projId}', [ProjectController::class, 'update']);
    Route::post('/file-attachmets', [ProjectController::class, 'storeAttachments']);
    Route::get('/remove-attachment/{attachmentid}', [ProjectController::class, 'removeAttachment']);
});

