<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController , App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require base_path('routes/routes/users.php');
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('refresh', [UserController::class, 'refresh']);
    Route::get('auth', [UserController::class, 'authentication']);
    require base_path('routes/routes/roles.php');
    require base_path('routes/routes/clients.php');
    require base_path('routes/routes/projects.php');
    require base_path('routes/routes/inquries.php');
    require base_path('routes/routes/invoices.php');
    require base_path('routes/routes/payments.php');
    require base_path('routes/routes/countries.php');
    require base_path('routes/routes/states.php');
    require base_path('routes/routes/cities.php');
    require base_path('routes/routes/categories.php');
    require base_path('routes/routes/subcategories.php');
    require base_path('routes/routes/expenses.php');
    Route::prefix('permissions')->group(function (): void {
        Route::get('', [RoleController::class, 'getAllPermissions']);
    });
});
