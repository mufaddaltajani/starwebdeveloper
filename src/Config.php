<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

class Config
{
    private static array $env = [];
    private static bool $loaded = false;

    /**
     * Parses the .env file exactly once per request.
     */
    private static function load(): void
    {
        $path = __DIR__ . '/../.env';

        if (!file_exists($path)) {
            throw new RuntimeException("CRITICAL: The .env file is missing.");
        }

        // Read file into an array, ignoring empty lines and newlines
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignore comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Split the line into Key and Value safely
            if (strpos($line, '=') !== false) {
                [$name, $value] = explode('=', $line, 2);
                self::$env[trim($name)] = trim($value);
            }
        }

        self::$loaded = true;
    }

    /**
     * Retrieve a configuration value by its key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$env[$key] ?? $default;
    }
}