<?php

declare(strict_types=1);

// Bootstrap the application
require_once __DIR__ . '/../src/autoload.php';

use App\Database;

echo "Starting Master Database Migrations...\n";
echo "--------------------------------------\n";

try {
    $db = Database::getConnection();

    // 1. Users Table Schema
    // We MUST create this first because the posts table depends on it.
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE UNIQUE INDEX IF NOT EXISTS idx_users_email ON users(email);
    ";

    // 2. Posts Table Schema
    // We define the strict FOREIGN KEY constraint directly in the table creation.
    $sql .= "
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            author_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            content TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'draft',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE UNIQUE INDEX IF NOT EXISTS idx_posts_slug ON posts(slug);
        CREATE INDEX IF NOT EXISTS idx_posts_status ON posts(status);
    ";

    // Execute the consolidated schema
    $db->exec($sql);

    echo "✅ 'users' table is ready.\n";
    echo "✅ 'posts' table is ready with Foreign Key constraints.\n";
    echo "--------------------------------------\n";
    echo "Migrations completed successfully.\n";

} catch (Exception $e) {
    echo "❌ Migration Failed: " . $e->getMessage() . "\n";
    exit(1);
}