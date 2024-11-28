<?php

use Illuminate\Support\Facades\Route;

Route::any('/login', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/debug', [\App\Http\Controllers\Api\CategoriesController::class, 'index']);
Route::any('/', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/create', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/edit/{id}', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/summary', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/rules', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/rules/create', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/operations/rules/edit/{id}', [\App\Http\Controllers\Controller::class, 'vue']);

Route::any('/categories', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/categories-tree', [\App\Http\Controllers\Controller::class, 'vue']);

Route::any('/contractors/{id}', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/contractors/{id}/rules', [\App\Http\Controllers\Controller::class, 'vue']);
Route::any('/contractors/{id}/operations', [\App\Http\Controllers\Controller::class, 'vue']);

Route::any('/contractors', [\App\Http\Controllers\Controller::class, 'vue']);