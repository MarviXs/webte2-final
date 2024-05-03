<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/change-password', [AuthController::class, 'change_password']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']);
        Route::post('/', [QuestionController::class, 'store']);
        Route::get('/{question}', [QuestionController::class, 'show']);
        Route::put('/{question}', [QuestionController::class, 'update']);
        Route::delete('/{question}', [QuestionController::class, 'destroy']);
        Route::post('/{question}/choices', [ChoiceController::class, 'store']);
        Route::post('/{question}/copy', [QuestionController::class, 'copy']);
    });

    Route::prefix('choices')->group(function () {
        Route::put('/{choice_id}', [ChoiceController::class, 'update']);
        Route::delete('/{choice_id}', [ChoiceController::class, 'destroy']);
    });

    Route::prefix('vote')->group(function () {
        Route::get('/{code}/results-archive/{closure_id}', [VoteController::class, 'result_archive']);
        Route::get('/{code}/results-comparison/', [VoteController::class, 'result_compare']);
        Route::get('/{code}/closures', [VoteController::class, 'closures']);
        Route::post('/{code}/close', [VoteController::class, 'close']);
    });

    Route::prefix('admin')->group(function () {
        Route::get('/questions', [QuestionController::class, 'index_admin']);

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::put('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
        });
    });
});

// Public vote routes
Route::prefix('vote')->group(function () {
    Route::get('/{code}', [VoteController::class, 'show_question']);
    Route::post('/{code}/answer', [VoteController::class, 'answer']);
    Route::get('/{code}/results', [VoteController::class, 'result']);
});

