<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\BrevoMailer;
use App\Services\RateLimiter;

class HomeController extends Controller
{
    /**
     * Renders the main landing page.
     */
    public function index(): string
    {
        // We pass the AEO/SEO metadata arrays here. 
        // Notice we don't pass a 3rd parameter, so it defaults to the public 'layout'
        return $this->render('home', [
            'meta' => [
                'title' => 'AI & Web Development Solutions | StarWebDev',
                'description' => 'Lightning-fast web applications and custom AI chatbots for modern enterprises.'
            ]
        ]);
    }

    /**
     * Renders the About page.
     */
    public function about(): string
    {
        return $this->render('about', [
            'meta' => [
                'title' => 'About Us | StarWebDev',
                'description' => 'Learn about our engineering standards and global mission.'
            ],
            'scripts' => [
                '/assets/js/form-validation.js' // It will load strictly on this page
            ]
        ]);
    }

    /**
     * Renders the Contact page.
     */
    public function contact(): string
    {
        return $this->render('contact', [
            'meta' => [
                'title' => 'Contact Us | StarWebDev',
                'description' => 'Get in touch to start your custom web or AI project today.'
            ],
            'scripts' => [
                '/assets/js/form-validation.js',
                '/assets/js/contact.js',
            ],
        ]);
    }

    public function submitContact(): void
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $rateLimiter = new RateLimiter();

        if ($rateLimiter->isBlocked("contact_$ipAddress", 3)) {
            $_SESSION['error'] = 'Too many requests. Please try again later.';
            header('Location: /contact');
            exit;
        }

        // Sanitize Input
        $data = [
            'name' => trim(htmlspecialchars($_POST['name'] ?? '')),
            'email' => trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL)),
            'phone' => trim(htmlspecialchars($_POST['phone'] ?? '')),
            'message' => trim(htmlspecialchars($_POST['message'] ?? ''))
        ];

        // Validation 1: Empty Fields
        if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['message'])) {
            $rateLimiter->recordFailedAttempt("contact_$ipAddress", 3);
            $_SESSION['error'] = 'All fields are strictly required.';
            $_SESSION['old_input'] = $data; // Flash the data
            header('Location: /contact');
            exit;
        }

        // Validation 2: Email Format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $rateLimiter->recordFailedAttempt("contact_$ipAddress", 3);
            $_SESSION['error'] = 'Please provide a valid email address.';
            $_SESSION['old_input'] = $data; // Flash the data
            header('Location: /contact');
            exit;
        }

        // Send Email
        $mailer = new BrevoMailer();
        if ($mailer->sendContactAlert($data)) {
            $_SESSION['success'] = 'Your message has been sent successfully. We will contact you shortly.';
            unset($_SESSION['old_input']); // Clear input on success
        } else {
            $_SESSION['error'] = 'An error occurred while sending your message. Please try again later.';
            $_SESSION['old_input'] = $data; // Flash the data
        }

        header('Location: /contact');
        exit;
    }
}