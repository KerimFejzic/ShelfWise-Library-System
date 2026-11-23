
<?php
// Router for PHP's built-in development server.

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// Serve existing files directly (including Swagger).
if ($path !== '/' && is_file($file)) {
    return false;
}

// Serve directory index pages.
if ($path !== '/' && is_dir($file) && is_file($file . '/index.html')) {
    return false;
}

// All API routes go through FlightPHP.
require __DIR__ . '/index.php';
