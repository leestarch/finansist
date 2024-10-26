<?php


use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'operations'], function (){
    Route::get('/', [\App\Http\Controllers\Api\OperationController::class, 'index']);
});
