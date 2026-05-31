<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

abstract class Model
{
    // The database connection instance
    protected PDO $db;
    
    // The table name (Child classes MUST define this)
    protected string $table;

    public function __construct()
    {
        // Inject the secure, singleton database connection
        $this->db = Database::getConnection();
    }

    /**
     * Retrieve a single record by its ID.
     * Returns an associative array, or null if not found (forces defensive programming).
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $result = $stmt->fetch();
        
        return $result ?: null;
    }

    /**
     * Retrieve all records from the table.
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll();
    }

    /**
     * Dynamically insert a new record into the database.
     * * @param array<string, mixed> $data Associative array of column => value
     * @return int The ID of the newly created record
     */
    public function create(array $data): int
    {
        // 1. Extract column names from the array keys
        $columns = implode(', ', array_keys($data));
        
        // 2. Generate the exact number of '?' placeholders for secure binding
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        // 3. Build the SQL string
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        
        // 4. Prepare and execute using the array values securely
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        // Return the new row's ID
        return (int) $this->db->lastInsertId();
    }

    /**
     * Dynamically update an existing record by its ID.
     * * @param int $id The ID of the record to update
     * @param array<string, mixed> $data Associative array of column => value
     */
    public function update(int $id, array $data): bool
    {
        // 1. Build the SET clause (e.g., "title = ?, content = ?")
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
        
        // 2. Build the SQL string
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";
        
        // 3. Prepare the statement
        $stmt = $this->db->prepare($sql);
        
        // 4. Combine the data values and the ID for execution
        $values = array_values($data);
        $values[] = $id; // Add the ID to the end of the array for the WHERE clause
        
        return $stmt->execute($values);
    }

    /**
     * Delete a record by its ID.
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$id]);
    }
}