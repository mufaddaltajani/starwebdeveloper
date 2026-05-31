<?php

declare(strict_types=1);

namespace App\Models;

class User extends Model
{
    // We simply tell the Base Model which table to operate on.
    protected string $table = 'users';
    
    /**
     * Find a user by their email address (Crucial for the login system).
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}