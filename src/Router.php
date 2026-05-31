<?php

declare(strict_types=1);

namespace App;

class Router
{
    // We store registered routes grouped by HTTP method for speed and security.
    private array $routes = [
        'GET'  => [],
        'POST' => []
    ];

    /**
     * Register a GET route.
     * $action can be a function or an array like [Controller::class, 'methodName']
     */
    public function get(string $uri, callable|array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, callable|array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Match the incoming request to a route and execute it.
     */
    public function resolve(string $requestUri, string $requestMethod): mixed
    {
        // 1. Strip query strings from the URI (e.g., /blog?page=2 becomes /blog)
        // This is a crucial edge case for accurate route matching.
        $uri = parse_url($requestUri, PHP_URL_PATH);
        
        // Edge case: If parse_url fails due to a deeply malformed URL, default to root.
        if ($uri === false || $uri === null) {
            $uri = '/';
        }

        // 2. Find the action mapped to this Method and URI
        $action = $this->routes[$requestMethod][$uri] ?? null;

        // 3. Handle 404 Not Found securely
        if ($action === null) {
            http_response_code(404);
            return "404 Not Found: The requested resource does not exist.";
        }

        // 4. Execute the action if it's a simple function (Closure)
        if (is_callable($action)) {
            return $action();
        }

        // 5. Execute the action if it's a Controller class and method
        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class)) {
                $controller = new $class();
                
                if (method_exists($controller, $method)) {
                    // Dynamically call the method on the instantiated controller
                    return $controller->$method();
                }
            }
        }

        // 6. Handle misconfigured routes
        http_response_code(500);
        return "500 Internal Server Error: Invalid Route Configuration.";
    }
}