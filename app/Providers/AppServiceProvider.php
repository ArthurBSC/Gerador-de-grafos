<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

/**
 * Provider principal da aplicação - Sistema Gerador de Grafos
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar serviços no container
     */
    public function register(): void
    {
        // Registrar serviços específicos da aplicação
        $this->app->singleton(\App\Utils\GeradorCores::class);
        $this->app->singleton(\App\Services\GrafoService::class);
        $this->app->singleton(\App\Services\DijkstraService::class);
    }

    /**
     * Bootstrap de serviços da aplicação
     */
    public function boot(): void
    {
        // Configurações específicas para desenvolvimento
        if ($this->app->environment(['local', 'development'])) {
            // Configurações de desenvolvimento podem ser adicionadas aqui
        }
    }
}
