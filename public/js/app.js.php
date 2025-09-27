<?php
// Forçar tipo MIME correto para JavaScript
header('Content-Type: application/javascript');
header('Cache-Control: public, max-age=31536000');

// Ler e servir o arquivo JS original
$jsFile = __DIR__ . '/app.js';
if (file_exists($jsFile)) {
    readfile($jsFile);
} else {
    http_response_code(404);
    echo "/* JS file not found */";
}
exit;
