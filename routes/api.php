<?php

use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {
        Route::apiResource('/tasks', TaskController::class);
    })
    ->group(function () {
        Route::apiResource('/labels', LabelController::class);
    });
