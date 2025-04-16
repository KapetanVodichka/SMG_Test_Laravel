<?php

use App\Http\Controllers\EntityController;
use Illuminate\Support\Facades\Route;

Route::get('/{entityType}', [EntityController::class, 'index']);
Route::post('/{entityType}', [EntityController::class, 'store']);
Route::get('/{entityType}/fetch', [EntityController::class, 'fetchAndSave']);
