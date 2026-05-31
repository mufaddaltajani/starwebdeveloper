<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    /**
     * Renders a view file within the master layout.
     * * @param string $view The view filename (without .php)
     * @param array<string, mixed> $data Variables to extract into the view
     * @param array<string, mixed> $meta SEO and AEO metadata
     */
    protected function render(string $view, array $data = [], array $meta = []): string
    {
        // 1. Extract data into variables (e.g., ['title' => 'Home'] becomes $title)
        // Using EXTR_SKIP prevents overwriting existing variables for security.
        extract($data, EXTR_SKIP);

        // 2. Start Output Buffering. 
        // This holds the generated HTML in memory instead of sending it to the browser immediately.
        ob_start();

        // 3. Include the specific view (e.g., 'home' or 'about')
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>View Error: Template not found.</h1>";
        }

        // 4. Capture the view content and clean the buffer
        $content = ob_get_clean();

        // 5. Start a new buffer for the master layout, passing the captured $content and $meta
        ob_start();
        require __DIR__ . '/../../views/layout.php';
        
        return ob_get_clean();
    }
}