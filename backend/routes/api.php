<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\VoteController;
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
    Route::put('/{id}', [QuestionController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [QuestionController::class, 'destroy'])->middleware('auth:api');
    Route::post('/{id}/close', [QuestionController::class, 'close'])->middleware('auth:api');;
    Route::get('/{question_id}/result_archive/{closure_id}', [VoteController::class, 'result_archive'])->middleware('auth:api');

    Route::prefix('{question_id}/choices')->group(function () {
        Route::post('/', [ChoiceController::class, 'store'])->middleware('auth:api');
        Route::put('/{choice}', [ChoiceController::class, 'update'])->middleware('auth:api');
        Route::delete('/{choice}', [ChoiceController::class, 'destroy'])->middleware('auth:api');
    });
});


Route::prefix('vote')->group(function () {
    Route::post('/{code}', [VoteController::class, 'show_question']);
    Route::post('/{code}/answer', [VoteController::class, 'answer']);
    Route::get('/{code}/result', [VoteController::class, 'result']);
});
