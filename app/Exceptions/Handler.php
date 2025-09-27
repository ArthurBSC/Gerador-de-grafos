<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Lista de tipos de exceção que não são reportados
     */
    protected $dontReport = [
        //
    ];

    /**
     * Lista de inputs que nunca são inseridos nos logs
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Registrar callbacks de tratamento de exceções
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

