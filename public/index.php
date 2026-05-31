<?php

declare(strict_types=1);

// 1. Load the PSR-4 Autoloader
// From here on, we NEVER need to use require_once again.
require_once __DIR__ . '/../src/autoload.php';

use App\Router;

// 2. Instantiate our new Router
$router = new Router();

// 3. Define our application routes
$router->get('/', function () {
    return "<h1>Welcome to the Star Web Developer.</h1><p>Routing is working perfectly.</p>";
});

$router->get('/about', function () {
    return "<h1>About Us</h1><p>We are building a scalable, accessible platform natively on Docker.</p>";
});

// 4. Resolve the current request and output the result
// We capture variables safely from PHP's superglobals
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

echo $router->resolve($requestUri, $requestMethod);