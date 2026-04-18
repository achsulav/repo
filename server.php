<?php

/**
 * PHP Built-in Server Router
 * Forwards all requests to public/index.php unless a real static file exists.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve real static files (css, js, images, etc.) directly
$file = __DIR__ . '/public' . $uri;
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // let PHP serve it
}

// Everything else → front controller
require __DIR__ . '/public/index.php';
