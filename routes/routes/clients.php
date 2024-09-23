<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::prefix('clients')->group(function () {
    Route::get('', [ClientController::class, 'index']);
    Route::get('/get_all_clients', [ClientController::class, 'getAllClients']);
    Route::get('/currencies', [ClientController::class, 'currencies']);
    Route::get('/{clientId}', [ClientController::class, 'getClient']);
    Route::post('/create', [ClientController::class, 'create']);
    Route::put('/update/{clientId}', [ClientController::class, 'update']);
    Route::get('{clientId}/delete', [ClientController::class, 'destroy']);

    // Client Notes
    Route::get('/notes', [ClientController::class, 'getNotes']);
    Route::post('/note/create', [ClientController::class, 'createNote']);
    Route::put('/note/update/{noteId}', [ClientController::class, 'updateNote']);
    Route::get('note/{noteId}/delete', [ClientController::class, 'destroyNote']);
});

