<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'operations'], function (){
    Route::get('/', [\App\Http\Controllers\Api\OperationController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\OperationController::class, 'show']);
    Route::get('/summary', [\App\Http\Controllers\Api\OperationController::class, 'summary']);
    Route::get('/create', [\App\Http\Controllers\Api\OperationController::class, 'create']);
    Route::post('/', [\App\Http\Controllers\Api\OperationController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\Api\OperationController::class, 'update']);

    Route::group(['prefix' => 'rules'], function (){
        Route::get('/', [\App\Http\Controllers\Api\OperationRuleController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\OperationRuleController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\OperationRuleController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\OperationRuleController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\OperationRuleController::class, 'destroy']);
    });
});

Route::prefix('categories')->group(function () {
    Route::get('/tree', [\App\Http\Controllers\Api\CategoriesController::class, 'indexTree']);
    Route::get('/', [\App\Http\Controllers\Api\CategoriesController::class, 'index']);
});

Route::prefix('contractors')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ContractorController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\ContractorController::class, 'show']);
    Route::get('/{id}/check', [\App\Http\Controllers\Api\ContractorController::class, 'operationCheck']);
});

Route::prefix('pizzerias')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PizzeriaController::class, 'index']);
});