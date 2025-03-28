 <?php

use App\Http\Controllers\spending_type_controller;
use Illuminate\Support\Facades\Route;

Route::get('/spending_type', [spending_type_controller::class, 'index']); 
Route::post('/spending_type', [spending_type_controller::class, 'store']); 
Route::get('/spending_type/{id}', [spending_type_controller::class, 'show']); 
Route::put('/spending_type/{id}', [spending_type_controller::class, 'update']); 
Route::delete('/spending_type/{id}', [spending_type_controller::class, 'destroy']);