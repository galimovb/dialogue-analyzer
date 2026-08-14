<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Dialogues\Controllers\DialogueController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dialogues', [DialogueController::class, 'index']);
    Route::get('/dialogues/{id}', [DialogueController::class, 'show']);
    Route::get('/dialogues/{id}/messages', [DialogueController::class, 'messages']);
});
