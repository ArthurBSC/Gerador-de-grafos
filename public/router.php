<?php
/**
 * Router para servir arquivos estáticos e Laravel
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Debug: Log das requisições
error_log("Router: Requesting URI: " . $uri);

// Se for um arquivo estático (CSS, JS, imagens, etc.)
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    $file = __DIR__ . $uri;
    
    error_log("Router: Static file requested: " . $file);
    
    if (file_exists($file)) {
        // Determinar o tipo MIME com fallbacks
        $mimeType = mime_content_type($file);
        if ($mimeType === false || $mimeType === 'text/plain') {
            // Fallback baseado na extensão
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
        }
        
        error_log("Router: Serving file: " . $file . " with MIME: " . $mimeType);
        
        // Definir headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: public, max-age=31536000'); // Cache por 1 ano
        
        // Servir o arquivo
        readfile($file);
        exit;
    } else {
        error_log("Router: File not found: " . $file);
        http_response_code(404);
        echo "File not found: " . $uri;
        exit;
    }
}

// Se for o healthcheck
if ($uri === '/health.php') {
    include __DIR__ . '/health.php';
    exit;
}

// Para todas as outras requisições, usar o Laravel
require_once __DIR__ . '/index.php';
