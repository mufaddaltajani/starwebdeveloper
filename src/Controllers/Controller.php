<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    /**
     * Renders a view file within a specified layout.
     * * @param string $view The view filename (without .php)
     * @param array<string, mixed> $data Variables to extract into the view
     * @param string $layout The master layout to wrap the view in
     */
    protected function render(string $view, array $data = [], string $layout = 'layout'): string
    {
        extract($data, EXTR_SKIP);

        // 1. Buffer the specific view (e.g., 'dashboard' or 'home')
        ob_start();
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>View Error: Template '$view' not found.</h1>";
        }
        $content = ob_get_clean();

        // 2. Buffer the layout and inject the view content
        ob_start();
        $layoutPath = __DIR__ . '/../../views/' . $layout . '.php';
        
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo "<h1>View Error: Layout '$layout' not found.</h1>";
        }
        
        return ob_get_clean();
    }
}