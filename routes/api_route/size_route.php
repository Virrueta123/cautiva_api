<?php
 
use App\Http\Controllers\size_controller;
use Illuminate\Support\Facades\Route;
Route::get('/size', [size_controller::class, 'index']); 