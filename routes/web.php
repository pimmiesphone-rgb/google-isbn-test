<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\IsbnController::class, 'index']);
