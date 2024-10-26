<?php

use Illuminate\Support\Facades\Route;

Route::any('/', [\App\Http\Controllers\Controller::class, 'vue'])->name('home');

