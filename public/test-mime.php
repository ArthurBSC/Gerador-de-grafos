<?php
// Teste de tipos MIME
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/css/app.css';

if (file_exists($file)) {
    header('Content-Type: text/css');
    header('Content-Length: ' . filesize($file));
    readfile($file);
} else {
    echo "File not found: " . $file;
}
exit;
