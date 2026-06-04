<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Services\RateLimiter;
use App\Config;

class AuthController extends Controller
{
    public function showLoginForm(): string
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }

        return $this->render('login', [
            'meta' => [
                'title' => 'Log In | Star Web Developer',
                'description' => 'Secure Admin Access'
            ], 
            'scripts' => [
                '/assets/js/form-validation.js',
                // Cloudflare Turnstile API Script
                'https://challenges.cloudflare.com/turnstile/v0/api.js' 
            ],
            'turnstile_site_key' => Config::get('TURNSTILE_SITE_KEY', ''),
        ], 'auth_layout');
    }

    public function login(): void
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $rateLimiter = new RateLimiter();

        // 1. Check Brute Force Ban FIRST (Saves CPU and network calls)
        if ($rateLimiter->isBlocked($ipAddress)) {
            $_SESSION['error'] = 'Too many failed attempts. Please try again in 15 minutes.';
            header('Location: /login');
            exit;
        }

        // 2. Cloudflare Turnstile Verification
        $turnstileResponse = $_POST['cf-turnstile-response'] ?? '';
        $turnstileSecret = Config::get('TURNSTILE_SECRET_KEY', '');

        // Skip validation if you are testing locally without internet, but enable in production
        if (!empty($turnstileResponse)) {
            $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
            $data = [
                'secret' => $turnstileSecret,
                'response' => $turnstileResponse,
                'remoteip' => $ipAddress
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];
            
            $context  = stream_context_create($options);
            $result = file_get_contents($verifyUrl, false, $context);
            $outcome = json_decode($result ?? '', true);

            if (!$outcome || empty($outcome['success'])) {
                $this->failLogin($rateLimiter, $ipAddress, 'Security check failed. Please try again.');
            }
        } else {
            // Require Turnstile response
            $this->failLogin($rateLimiter, $ipAddress, 'Please complete the security check.');
        }

        // 3. Sanitize input
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->failLogin($rateLimiter, $ipAddress, 'Email and password are required.', $email);
        }

        // 4. Database Verification
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Success: Clear failed attempts
            $rateLimiter->clearAttempts($ipAddress);
            
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            unset($_SESSION['error']);
            unset($_SESSION['old_email']);
            
            header('Location: /admin/dashboard');
            exit;
        }

        // 5. Authentication Failed
        $this->failLogin($rateLimiter, $ipAddress, 'Invalid credentials. Please try again.', $email);
    }

    /**
     * Helper method to handle failed logins cleanly.
     */
    private function failLogin(RateLimiter $rateLimiter, string $ipAddress, string $message, string $oldEmail = ''): void
    {
        // Record the attempt. If it returns false, the 15 min ban has been triggered.
        if (!$rateLimiter->recordFailedAttempt($ipAddress)) {
            $_SESSION['error'] = 'Too many failed attempts. Please try again in 15 minutes.';
        } else {
            $_SESSION['error'] = $message;
        }

        // Flash the old email back to the session so the user doesn't have to retype it
        if (!empty($oldEmail)) {
            $_SESSION['old_email'] = $oldEmail;
        }

        header('Location: /login');
        exit;
    }

    /**
     * Log the user out and destroy the session.
     */
    public function logout(): void
    {
        $_SESSION = []; // Clear session array
        session_destroy(); // Destroy session file on server
        
        // Delete the session cookie from the browser
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );

        header('Location: /login');
        exit;
    }
}