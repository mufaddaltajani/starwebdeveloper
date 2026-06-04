<?php

declare(strict_types=1);

namespace App\Services;

use Redis;
use Exception;

class RateLimiter
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        // Connect to the 'redis' container defined in our Docker setup
        $this->redis->connect('redis', 6379);
    }

    /**
     * Checks if the IP is blocked. If not, records a failed attempt.
     * Returns true if the user is allowed to proceed, false if they are blocked.
     */
    public function recordFailedAttempt(string $ipAddress, int $maxAttempts = 5, int $decaySeconds = 900): bool
    {
        $key = "login_attempts:{$ipAddress}";
        
        try {
            $attempts = $this->redis->get($key);

            if ($attempts !== false && (int)$attempts >= $maxAttempts) {
                return false; // User is blocked
            }

            // Increment the attempt count
            $newCount = $this->redis->incr($key);

            // If this is the first failure, set the 15-minute (900s) expiration timer
            if ($newCount === 1) {
                $this->redis->expire($key, $decaySeconds);
            }

            return true;
        } catch (Exception $e) {
            // Fail open: If Redis crashes, don't lock the admin out completely, 
            // but log the error in a real production environment.
            return true; 
        }
    }

    /**
     * Clears the failed attempts upon a successful login.
     */
    public function clearAttempts(string $ipAddress): void
    {
        $this->redis->del("login_attempts:{$ipAddress}");
    }

    /**
     * Checks if the IP is currently blocked without incrementing.
     */
    public function isBlocked(string $ipAddress, int $maxAttempts = 5): bool
    {
        $attempts = $this->redis->get("login_attempts:{$ipAddress}");
        return $attempts !== false && (int)$attempts >= $maxAttempts;
    }
}