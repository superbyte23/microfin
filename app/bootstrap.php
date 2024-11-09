<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/utils/functions.php';

use App\Router;

$router = new Router();

// Get the current request URI and method
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Include the routes file
require __DIR__ . '/../routes/web.php';

// Resolve the route 

$router->resolve($uri, $method);
