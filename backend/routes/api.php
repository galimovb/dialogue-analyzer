<?php

use App\Modules\Analysis\Controllers\AnalysisEventController;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Dialogues\Controllers\DialogueController;
use App\Modules\Rules\Controllers\RuleController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dialogues', [DialogueController::class, 'index']);
    Route::get('/dialogues/{id}', [DialogueController::class, 'show']);
    Route::get('/dialogues/{id}/messages', [DialogueController::class, 'messages']);
    Route::get('/dialogues/{id}/events', [AnalysisEventController::class, 'index']);

    // Управление правилами анализа — только для админа.
    Route::middleware('admin')->group(function () {
        Route::get('/rules', [RuleController::class, 'index']);
        Route::patch('/rules/{id}', [RuleController::class, 'update']);
    });
});
