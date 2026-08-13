<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'service' => 'dialogue-analyzer API',
    'status' => 'ok',
]));
