<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Render the login form.
     */
    public function showLoginForm(): string
    {
        // If the user is already logged in, redirect them away from the login page
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }

        return $this->render('login', [
            'title' => 'Admin Login | Star Web Developer',
            'description' => 'Secure administrator login gateway.'
        ]);
    }

    /**
     * Process the login POST request.
     */
    public function login(): void
    {
        // 1. Sanitize input
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        // 2. Validate input presence
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required.';
            header('Location: /login');
            exit;
        }

        // 3. Find the user in the database
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // 4. Secure Password Verification
        // We never compare passwords directly. We use password_verify() to compare
        // the plain-text input against the Argon2id hash in the database safely.
        if ($user && password_verify($password, $user['password'])) {
            
            // 5. Prevent Session Fixation attacks by regenerating the ID upon login
            session_regenerate_id(true);
            
            // 6. Set authenticated session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            // Clear any old errors
            unset($_SESSION['error']);
            
            // Redirect to the protected area
            header('Location: /admin/dashboard');
            exit;
        }

        // 7. Generic Error Message (Security Best Practice)
        // Never tell the user "Email not found" or "Incorrect password". 
        // That allows attackers to enumerate registered emails. Always use a generic message.
        $_SESSION['error'] = 'Invalid credentials. Please try again.';
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