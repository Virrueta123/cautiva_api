<?php

use App\Http\Controllers\box_controller;
use Illuminate\Support\Facades\Route;

Route::get('/box', [box_controller::class, 'index']); 
Route::get('/box/{identifier}', [box_controller::class, 'show']); 
Route::get('/box/box_sale/{identifier}', [box_controller::class, 'box_sale']); 
Route::get('/box/box_sending/{identifier}', [box_controller::class, 'box_sending']); 
Route::post('/box', [box_controller::class, 'store']); 
Route::put('/box/close/{identifier}', [box_controller::class, 'close']);
Route::delete('/box/{identifier}', [box_controller::class, 'destroy']);