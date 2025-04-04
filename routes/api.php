<?php

use App\Http\Controllers\auth_controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [auth_controller::class, 'register']);
Route::post('/authentication', [auth_controller::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [auth_controller::class, 'logout']);
    Route::get('/user', [auth_controller::class, 'user']);
    require __DIR__ . '/api_route/product_route.php';
    require __DIR__ . '/api_route/model_route.php';
    require __DIR__ . '/api_route/category_route.php';
    require __DIR__ . '/api_route/account_route.php';
    require __DIR__ . '/api_route/client_route.php';
    require __DIR__ . '/api_route/api_peru_route.php';
    require __DIR__ . '/api_route/sale_route.php';
    require __DIR__ . '/api_route/box_route.php';
    require __DIR__ . '/api_route/size_route.php';
    require __DIR__ . '/api_route/spending_route.php';
    require __DIR__ . '/api_route/spending_type_route.php';
    require __DIR__ . '/api_route/report_route.php';
});
