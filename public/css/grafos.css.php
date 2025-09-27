<?php
// Forçar tipo MIME correto para CSS
header('Content-Type: text/css');
header('Cache-Control: public, max-age=31536000');

// Ler e servir o arquivo CSS original
$cssFile = __DIR__ . '/grafos.css';
if (file_exists($cssFile)) {
    readfile($cssFile);
} else {
    http_response_code(404);
    echo "/* CSS file not found */";
}
exit;