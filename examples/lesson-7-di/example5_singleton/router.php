<?php
// router.php
// Run web server: IS_POLITE=false php -d variables_order="EGPCS" -S localhost:8000 router.php

// If the requested file exists, serve it directly
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (file_exists(__DIR__ . $path)) {
    return false;
}
// Otherwise, route the request to index.php
require __DIR__ . '/index.php';
