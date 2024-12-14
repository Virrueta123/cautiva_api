<?php

use App\Http\Controllers\account_controller;
use Illuminate\Support\Facades\Route;

Route::get('/account/select', [account_controller::class, 'select']);