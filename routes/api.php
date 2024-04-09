<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::get('me', [UserAuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('users', UserController::class)->only(['index', 'update', 'show', 'destroy'])->middleware('auth:sanctum');
Route::apiResource('cards', CardController::class)->middleware('auth:sanctum');
Route::apiResource('expenses', ExpenseController::class)->middleware('auth:sanctum');
