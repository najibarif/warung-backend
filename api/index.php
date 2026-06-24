<?php
// Vercel sets SCRIPT_NAME=/api/index.php and PHP_SELF=/api/index.php/ping.
// Symfony prepareBaseUrl() uses these to calculate a base URL of /api,
// then strips it from the path info, breaking all API routes.
// Override both to the actual request URI to force baseUrl = ''.
$_SERVER['PHP_SELF'] = $_SERVER['REQUEST_URI'];
$_SERVER['SCRIPT_NAME'] = '';

require __DIR__ . '/../public/index.php';
