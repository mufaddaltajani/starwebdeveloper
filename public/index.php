<?php

declare(strict_types=1);
// Force the entire application into Coordinated Universal Time
date_default_timezone_set('UTC');

// 1. Load the PSR-4 Autoloader
// From here on, we NEVER need to use require_once again.
require_once __DIR__ . '/../src/autoload.php';

session_set_cookie_params([
    'lifetime' => 86400, // Session lasts for 24 hours
    'path' => '/',
    'domain' => '', // Leave blank to default to current domain
    'secure' => false, // ⚠️ Set to true in production when using HTTPS
    'httponly' => true, // Prevents JavaScript from reading the session cookie (XSS protection)
    'samesite' => 'Strict' // Prevents the browser from sending this cookie with cross-site requests (CSRF protection)
]);

session_start();

use App\Router;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\HomeController;

// 2. Instantiate our new Router
$router = new Router();

// --- PUBLIC ROUTES ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);
$router->post('/contact', [HomeController::class, 'submitContact']);

// Authentication Routes
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);

// 4. Resolve the current request and output the result
// We capture variables safely from PHP's superglobals
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

echo $router->resolve($requestUri, $requestMethod);