<?php
// Router personalizado para servir arquivos estáticos com MIME types corretos

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Healthcheck simples
if ($uri === '/health') {
    header('Content-Type: text/plain');
    echo 'OK';
    exit;
}

// Servir arquivos estáticos com MIME types corretos
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    $file = __DIR__ . $uri;
    
    if (file_exists($file)) {
        // Definir MIME types corretos
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'font/eot'
        ];
        
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        
        readfile($file);
        exit;
    }
}

// Para todas as outras requisições, usar o Laravel original
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);