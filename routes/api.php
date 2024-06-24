<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('/token/refresh', [AuthController::class, 'refreshToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('answers', [AnswerController::class, 'store']);
    Route::put('/answers/{id}', [AnswerController::class, 'update']);
    Route::delete('/answers/{id}', [AnswerController::class, 'destroy']);
    Route::put('answers/{answer}/validate', [AnswerController::class, 'validateAnswer']);
    Route::post('/questions', [QuestionController::class, 'store']);
    Route::put('/questions/{id}', [QuestionController::class, 'update']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);
    Route::get('user', [AuthController::class, 'user']);
    Route::post('/check-auth', function (Request $request) {
        return response()->json(['authenticated' => true], 200);
    });
});

Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);
Route::get('/search', [QuestionController::class, 'search']);
