<?php

use Illuminate\Support\Facades\Route;

// Healthcheck endpoint para Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Sistema Gerador de Grafos',
        'version' => '2.0.0'
    ], 200);
});

// Healthcheck alternativo
Route::get('/healthcheck', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'service' => 'Sistema Gerador de Grafos',
        'version' => '2.0.0'
    ], 200);
});
