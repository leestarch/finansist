<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'operations'], function (){
    Route::get('/', [\App\Http\Controllers\Api\OperationController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\OperationController::class, 'show']);
    Route::get('/summary', [\App\Http\Controllers\Api\OperationController::class, 'summary']);
    Route::post('/', [\App\Http\Controllers\Api\OperationController::class, 'store']);
    Route::post('/seed', [\App\Http\Controllers\Api\OperationController::class, 'seed']);
    Route::put('/{id}', [\App\Http\Controllers\Api\OperationController::class, 'update']);
});

Route::group(['prefix' => 'rules'], function (){
    Route::get('/', [\App\Http\Controllers\Api\OperationRuleController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\OperationRuleController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\Api\OperationRuleController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\Api\OperationRuleController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\OperationRuleController::class, 'destroy']);
});

Route::prefix('categories')->group(function () {
    Route::get('/tree', [\App\Http\Controllers\Api\CategoriesController::class, 'indexTree']);
    Route::get('/', [\App\Http\Controllers\Api\CategoriesController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\CategoriesController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\Api\CategoriesController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\Api\CategoriesController::class, 'show']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\CategoriesController::class, 'destroy']);
});

Route::prefix('contractors')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ContractorController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\ContractorController::class, 'show']);
    Route::get('/{id}/check', [\App\Http\Controllers\Api\ContractorController::class, 'operationCheck']);
});

Route::prefix('pizzerias')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PizzeriaController::class, 'index']);
});

Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
