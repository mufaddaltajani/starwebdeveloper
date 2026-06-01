<?php

declare(strict_types=1);

// Bootstrap the application
require_once __DIR__ . '/../src/autoload.php';

use App\Models\User;

echo "--- Admin User Setup ---\n";

// 1. Interactively prompt the user for input in the CLI
$name = readline("Enter Admin Name: ");
$email = readline("Enter Admin Email: ");

// Use a simple prompt for the password. 
// (Note: In pure PHP CLI, hiding the password input is complex across different OSs, 
// so we will keep it simple here as it's a local development setup).
$password = readline("Enter Admin Password: ");

// 2. Defensive Programming: Validate input
if (empty($name) || empty($email) || empty($password)) {
    echo "❌ Error: All fields are required.\n";
    exit(1);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Error: Invalid email format.\n";
    exit(1);
}

try {
    $userModel = new User();

    // 3. Check for duplicates before attempting to insert
    if ($userModel->findByEmail($email) !== null) {
        echo "❌ Error: A user with the email '$email' already exists.\n";
        exit(1);
    }

    // 4. Securely hash the password using Argon2id
    // This is the current cryptographic standard. It is memory-hard, making it 
    // highly resistant to GPU brute-force attacks.
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

    // 5. Prepare the data array for our Base Model
    $userData = [
        'name' => trim($name),
        'email' => trim(strtolower($email)), // Normalize emails to lowercase
        'password' => $hashedPassword
    ];

    // 6. Insert into the database
    $newUserId = $userModel->create($userData);

    echo "✅ Success: Admin user '{$userData['name']}' created perfectly.\n";
    echo "🔑 ID: $newUserId\n";
    echo "------------------------\n";
    echo "Your data layer is officially online and populated.\n";

} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit(1);
}