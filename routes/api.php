<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', [\App\Http\Controllers\AmoController::class, 'webhook'])->name('amocrm.webhook');
