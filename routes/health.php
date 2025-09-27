<?php

// Healthcheck simples sem dependências do Laravel
if (!function_exists('healthcheck_response')) {
    function healthcheck_response() {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'timestamp' => date('c'),
            'service' => 'Sistema Gerador de Grafos',
            'version' => '2.0.0'
        ]);
        exit;
    }
}

// Healthcheck endpoint para Railway - versão simples
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/health') {
    healthcheck_response();
}

// Healthcheck alternativo
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/healthcheck') {
    healthcheck_response();
}
