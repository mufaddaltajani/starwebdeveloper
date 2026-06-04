<?php

declare(strict_types=1);

namespace App\Services;

use App\Config;
use Exception;

class BrevoMailer
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        // Fetch values from the .env file safely
        $this->apiKey = Config::get('BREVO_API_KEY', '');
        $this->apiUrl = Config::get('BREVO_API_URL', 'https://api.brevo.com/v3/smtp/email');
        
        if (empty($this->apiKey)) {
            throw new Exception("Brevo API Key is missing from environment configuration.");
        }
    }

    /**
     * Sends the contact form data to your email via Brevo.
     */
    public function sendContactAlert(array $formData): bool
    {
        // 1. Prepare the JSON payload for Brevo
        $payload = json_encode([
            'sender' => [
                'name' => 'StarWebDev System',
                'email' => 'noreply@yourdomain.com' // Must be a verified sender in Brevo
            ],
            'to' => [
                [
                    'email' => 'starwebdevelopers5@gmail.com', // Where you want to receive the leads
                    'name' => 'Admin'
                ]
            ],
            'subject' => 'New Business Lead: ' . $formData['name'],
            'htmlContent' => "
                <h2>New Contact Request</h2>
                <p><strong>Name:</strong> " . htmlspecialchars($formData['name']) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($formData['email']) . "</p>
                <p><strong>Phone:</strong> " . htmlspecialchars($formData['phone']) . "</p>
                <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($formData['message'])) . "</p>
            "
        ]);

        // 2. Configure the native PHP HTTP POST request
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'api-key: ' . $this->apiKey
                ],
                'content' => $payload,
                'ignore_errors' => true // Allows us to read the error response if it fails
            ]
        ];

        try {
            $context  = stream_context_create($options);
            $result = file_get_contents($this->apiUrl, false, $context);
            $responseCode = $http_response_header[0] ?? '';

            // 201 Created is Brevo's success code for sending an email
            return strpos($responseCode, '201') !== false;
            
        } catch (Exception $e) {
            return false;
        }
    }
}