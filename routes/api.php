<?php

use App\Http\Controllers\GrafoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas API - Sistema Gerador de Grafos
|--------------------------------------------------------------------------
|
| Rotas da API REST para integração e funcionalidades AJAX
|
*/

Route::middleware(['api'])->group(function () {
    
    // Status geral da API
    Route::get('/status', function () {
        return response()->json([
            'api_status' => 'online',
            'versao' => '2.0.0',
            'timestamp' => now()->toISOString(),
            'sistema' => 'Gerador de Grafos Reestruturado',
            'laravel' => app()->version(),
            'php' => PHP_VERSION
        ]);
    });
    
    // Informações do sistema
    Route::get('/info', function () {
        return response()->json([
            'nome' => config('app.name'),
            'versao' => config('sistema.versao', '2.0.0'),
            'arquitetura' => config('sistema.arquitetura', 'Clean Code + SOLID + Laravel 9'),
            'laravel' => app()->version(),
            'php' => PHP_VERSION
        ]);
    });
    
    // Estatísticas do sistema
    Route::get('/estatisticas', function () {
        return response()->json([
            'grafos_total' => \App\Models\Grafo::count(),
            'nos_total' => \App\Models\NoGrafo::count(),
            'arestas_total' => \App\Models\ArestaGrafo::count(),
            'timestamp' => now()->toISOString()
        ]);
    });
    
    // Rotas de grafos via API
    Route::prefix('grafos')->group(function () {
        Route::get('/', [GrafoController::class, 'index']);
        Route::get('/{id}', [GrafoController::class, 'show'])->where('id', '[0-9]+');
        Route::post('/{id}/caminho-minimo', [GrafoController::class, 'calcularCaminhoMinimo'])->where('id', '[0-9]+');
    });
    
});
