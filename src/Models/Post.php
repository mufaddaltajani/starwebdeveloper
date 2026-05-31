<?php

declare(strict_types=1);

namespace App\Models;

class Post extends Model
{
    protected string $table = 'posts';

    /**
     * Find a post by its AEO/SEO slug (Crucial for the public blog view).
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}