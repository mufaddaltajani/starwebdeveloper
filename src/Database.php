<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Private constructor prevents direct instantiation (Singleton pattern).
     */
    private function __construct() {}

    /**
     * Returns a secure, optimized PDO connection to SQLite.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Locate the database file safely within the container
            $dbPath = __DIR__ . '/../database/app.sqlite';

            try {
                // Connect to SQLite
                self::$instance = new PDO('sqlite:' . $dbPath);

                // --- CRITICAL SENIOR-LEVEL CONFIGURATION ---
                
                // 1. Throw Exceptions on errors. Never fail silently.
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // 2. Return data as associative arrays by default (memory efficient)
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // 3. Disable emulated prepared statements. Forces the actual database engine 
                // to prepare the statement, making SQL injection mathematically impossible.
                self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                // 4. Enforce Foreign Key constraints (SQLite disables them by default)
                self::$instance->exec('PRAGMA foreign_keys = ON;');

            } catch (PDOException $e) {
                // In production, we log this message instead of showing it to the user.
                throw new RuntimeException("Database Connection Failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}