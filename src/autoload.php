<?php

declare(strict_types=1);

/**
 * Custom PSR-4 Autoloader
 * This function registers a closure that PHP will trigger whenever an unknown class is called.
 */
spl_autoload_register(function (string $class): void {
    // 1. Define our project's base namespace and directory
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/'; // Points directly to the /src folder

    // 2. Check if the class uses our namespace prefix
    // If it doesn't, we return immediately so other autoloaders (if any) can try.
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // 3. Strip the prefix to get the relative class name 
    // e.g., 'App\Controllers\HomeController' becomes 'Controllers\HomeController'
    $relative_class = substr($class, $len);

    // 4. Convert namespace separators (\) to directory separators (/)
    // e.g., 'Controllers\HomeController' becomes 'Controllers/HomeController'
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 5. If the file exists, safely require it.
    if (file_exists($file)) {
        require $file;
    }
});