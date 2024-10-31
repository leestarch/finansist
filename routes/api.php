<?php


use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'operations'], function (){
    Route::get('/', [\App\Http\Controllers\Api\OperationController::class, 'index']);
    Route::get('/summary', [\App\Http\Controllers\Api\OperationController::class, 'summary']);
    Route::get('/create', [\App\Http\Controllers\Api\OperationController::class, 'create']);
    Route::post('/', [\App\Http\Controllers\Api\OperationController::class, 'store']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [\App\Http\Controllers\CategoriesController::class, 'index']);
});