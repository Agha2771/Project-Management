<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;


Route::prefix('roles')->group(function () {
    Route::get('/permissions_list', [RoleController::class, 'getAllPermissions']);
    Route::post('/create', [RoleController::class, 'create']);
    Route::put('/update/{roleId}', [RoleController::class, 'update']);
    Route::get('/{role?}', [RoleController::class, 'getRoles']);
    Route::get('{roleId}/delete/{newRoleId?}', [RoleController::class, 'destroy']);
});

