<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('cards', CardController::class)->middleware('auth:sanctum');
Route::apiResource('expenses', ExpenseController::class)->middleware('auth:sanctum');
