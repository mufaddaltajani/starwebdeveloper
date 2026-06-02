<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTime;
use DateTimeZone;
use Exception;

class Time
{
    /**
     * Converts a UTC timestamp from the database to a localized format.
     */
    public static function display(string $utcDateTime, string $targetTimezone = 'Asia/Kolkata', string $format = 'M j, Y g:i A'): string
    {
        try {
            // 1. Tell PHP this string is currently in UTC
            $dt = new DateTime($utcDateTime, new DateTimeZone('UTC'));
            
            // 2. Convert it to the target timezone
            $dt->setTimezone(new DateTimeZone($targetTimezone));
            
            // 3. Output the formatted string
            return $dt->format($format);
            
        } catch (Exception $e) {
            // Defensive programming: fallback if the database string is malformed
            return 'Invalid Date';
        }
    }
}