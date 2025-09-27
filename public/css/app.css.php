<?php
header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
header('X-Content-Type-Options: nosniff');

$file = __DIR__ . '/app.css';
if (file_exists($file)) {
    readfile($file);
} else {
    http_response_code(404);
    echo '/* CSS file not found */';
}
?>