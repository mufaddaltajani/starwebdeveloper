<?php

declare(strict_types=1);

namespace App\Controllers;

class AdminController extends Controller
{
    public function __construct()
    {
        // --- MIDDLEWARE PATTERN ---
        // By putting this in the constructor, we guarantee that NO method 
        // in this entire controller can be executed without an active session.
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Show the main admin dashboard.
     */
    public function dashboard(): string
    {
        // Render the 'dashboard' view, wrap it in 'admin_layout'
        return $this->render('dashboard', [
            'title' => 'Dashboard | Secure Admin'
        ], 'admin_layout');
    }
}