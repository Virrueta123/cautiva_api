<?php

use App\Http\Controllers\category_controller;
use Illuminate\Support\Facades\Route;

Route::get('/category', [category_controller::class, 'index']); 