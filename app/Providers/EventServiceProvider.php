<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento de eventos para listeners
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Registrar eventos
     */
    public function boot()
    {
        //
    }

    /**
     * Auto-descobrir eventos e listeners
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}

