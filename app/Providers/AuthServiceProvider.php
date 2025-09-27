<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento de políticas para modelos
     */
    protected $policies = [
        //
    ];

    /**
     * Registrar serviços de autenticação/autorização
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}

