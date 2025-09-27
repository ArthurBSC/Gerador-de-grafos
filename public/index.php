<?php

// Servir arquivos estáticos antes do Laravel
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Se for um arquivo estático (CSS, JS, imagens, etc.)
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    $file = __DIR__ . $uri;
    
    if (file_exists($file)) {
        // Determinar o tipo MIME
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
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
        
        $mimeType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
        
        // Definir headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: public, max-age=31536000');
        
        // Servir o arquivo
        readfile($file);
        exit;
    }
}

// Se for o healthcheck
if ($uri === '/health.php') {
    header('Content-Type: text/plain');
    header('Cache-Control: no-cache');
    echo "OK";
    exit;
}

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);