<?php

use App\Http\Controllers\model_controller;
use Illuminate\Support\Facades\Route;

Route::get('/model', [model_controller::class, 'index']); 