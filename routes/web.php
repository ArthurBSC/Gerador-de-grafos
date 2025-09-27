<?php

use App\Http\Controllers\GrafoController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Rotas Otimizadas - Sistema Gerador de Grafos
|--------------------------------------------------------------------------
|
| Rotas otimizadas com melhor performance e organização
|
*/

// Página inicial com login
Route::get('/', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Sistema funcionando!',
        'timestamp' => now()->toISOString(),
        'next_step' => 'Acesse /grafos para ver a lista de grafos'
    ]);
})->name('home');

// Processamento do login
Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    
    // Debug
    \Log::info('Tentativa de login', ['email' => $email, 'password_length' => strlen($password)]);
    
    // Validação simples com credenciais hardcoded
    if ($email === 'admin@grafos.com' && $password === 'admin123') {
        // Simular sessão de login
        session(['user_logged_in' => true, 'user_email' => $email]);
        \Log::info('Login bem-sucedido', ['email' => $email]);
        return redirect()->route('grafos.index')->with('sucesso', 'Login realizado com sucesso!');
    } else {
        \Log::warning('Login falhou', ['email' => $email, 'password' => $password]);
        return back()->with('erro', 'Email ou senha incorretos!');
    }
})->name('login');

// Grupo de rotas para grafos (otimizado)
Route::prefix('grafos')->name('grafos.')->group(function () {

    // Listagem otimizada
    Route::get('/', [GrafoController::class, 'index'])
         ->name('index');
    
    // Formulário de criação
    Route::get('/criar', [GrafoController::class, 'create'])
         ->name('create');
    
    // Processamento da criação
    Route::post('/', [GrafoController::class, 'store'])
         ->name('store');
    
    // Visualização de grafo específico
    Route::get('/{id}', [GrafoController::class, 'show'])
         ->name('show')->where('id', '[0-9]+');
    
    // Edição de grafo
    Route::get('/{id}/editar', [GrafoController::class, 'edit'])
         ->name('edit')->where('id', '[0-9]+');
    
    // Atualização de grafo
    Route::put('/{id}', [GrafoController::class, 'update'])
         ->name('update')->where('id', '[0-9]+');
    
    // Exclusão de grafo
    Route::delete('/{id}', [GrafoController::class, 'destroy'])
         ->name('destroy')->where('id', '[0-9]+');
    
    // Cálculo de caminho mínimo
    Route::post('/{id}/caminho-minimo', [GrafoController::class, 'calcularCaminhoMinimo'])
         ->name('caminho-minimo')->where('id', '[0-9]+');
});

// Rota de logout
Route::get('/logout', function () {
    session()->forget(['user_logged_in', 'user_email']);
    return redirect('/')->with('sucesso', 'Logout realizado com sucesso!');
})->name('logout');

// Rotas de API básicas (para compatibilidade)
Route::prefix('api')->name('api.')->group(function () {
    
    // Status do sistema
    Route::get('/status', function () {
        return response()->json([
            'status' => 'online',
            'versao' => config('sistema.versao', '2.0.0'),
            'servidor' => 'Laravel ' . app()->version(),
            'php' => PHP_VERSION,
            'timestamp' => now()->toISOString(),
            'memoria_uso' => memory_get_usage(true),
            'tempo_resposta' => microtime(true) - LARAVEL_START
        ]);
    })->name('status');
});



// Rota de teste de ícones (apenas em desenvolvimento)

// Rotas de manutenção (apenas em desenvolvimento)
if (app()->environment(['local', 'development'])) {
    
    Route::prefix('manutencao')->name('manutencao.')->group(function () {
        
        // Limpar caches
        Route::post('/limpar-cache', function () {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            
            return response()->json([
                'sucesso' => true,
                'mensagem' => 'Caches limpos com sucesso',
                'timestamp' => now()->toISOString()
            ]);
        })->name('limpar-cache');
        
        // Reset do banco
        Route::post('/reset-banco', function () {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate:fresh');
                
                return response()->json([
                    'sucesso' => true,
                    'mensagem' => 'Banco resetado com sucesso',
                    'timestamp' => now()->toISOString()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'sucesso' => false,
                    'erro' => $e->getMessage()
                ], 500);
            }
        })->name('reset-banco');
    });
}

// Fallback otimizado
Route::fallback(function () {
    return redirect()->route('grafos.index')
                   ->with('aviso', 'Página não encontrada. Redirecionado para a lista de grafos.');
});
