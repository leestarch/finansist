<?php

use Illuminate\Support\Facades\Route;

Route::any('/', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/create', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/summary', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/categories', [\App\Http\Controllers\Controller::class, 'vue']);

