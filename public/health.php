<?php
// Healthcheck simples para Railway - funciona antes do Laravel carregar
http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'timestamp' => date('c'),
    'service' => 'Sistema Gerador de Grafos',
    'version' => '2.0.0',
    'message' => 'Service is healthy and ready'
]);
exit;
