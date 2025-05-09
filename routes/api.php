<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Webhooks routes
Route::get('/test', [\App\Http\Controllers\AmoController::class, 'test']);
Route::post('/webhook', [\App\Http\Controllers\AmoController::class, 'webhook'])->name('amocrm.webhook');
