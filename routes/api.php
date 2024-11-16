<?php


use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'operations'], function (){
    Route::get('/', [\App\Http\Controllers\Api\OperationController::class, 'index']);
    Route::get('/summary', [\App\Http\Controllers\Api\OperationController::class, 'summary']);
    Route::get('/create', [\App\Http\Controllers\Api\OperationController::class, 'create']);
    Route::post('/', [\App\Http\Controllers\Api\OperationController::class, 'store']);
    Route::post('/rules', [\App\Http\Controllers\Api\OperationController::class, 'storeRule']);
});

Route::prefix('categories')->group(function () {
    Route::get('/tree', [\App\Http\Controllers\Api\CategoriesController::class, 'indexTree']);
    Route::get('/', [\App\Http\Controllers\Api\CategoriesController::class, 'index']);
});

Route::prefix('contractors')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ContractorController::class, 'index']);
    Route::get('/{id}/check', [\App\Http\Controllers\Api\ContractorController::class, 'operationCheck']);
});