<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('/change-password', [AuthController::class, 'change_password'])->middleware('auth:api');
});

Route::prefix('questions')->group(function () {
    Route::get('/', [QuestionController::class, 'index'])->middleware('auth:api');
    Route::post('/', [QuestionController::class, 'store'])->middleware('auth:api');
    Route::get('/{id}', [QuestionController::class, 'show'])->middleware('auth:api');
    Route::put('/{question}', [QuestionController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [QuestionController::class, 'destroy'])->middleware('auth:api');
});
