<?php

declare(strict_types=1);

// show all errors
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../app.php';
require_once LIB . 'router.php';
require_once LIB . 'event.php';
require_once LIB . 'http.php';

session_start();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

echo route($path, require SRC . 'routes.php');
