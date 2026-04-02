<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Common\Exception\AuthenticationException;
use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// Login
try {
    $auth = $essabu->identity->auth->login([
        'email' => 'admin@company.com',
        'password' => 'secure-password',
    ]);

    echo "Token: {$auth['token']}\n";
    echo "User: {$auth['user']['email']}\n";

    // Refresh token
    $newAuth = $essabu->identity->auth->refresh($auth['refreshToken']);
    echo "New token: {$newAuth['token']}\n";

    // Get current profile
    $profile = $essabu->identity->profile->me();
    echo "Logged in as: {$profile['firstName']} {$profile['lastName']}\n";

    // Change password
    $essabu->identity->profile->changePassword([
        'currentPassword' => 'old-password',
        'newPassword' => 'new-secure-password',
    ]);

    // Logout
    $essabu->identity->auth->logout();
    echo "Logged out successfully.\n";
} catch (AuthenticationException $e) {
    echo "Authentication failed: {$e->getMessage()}\n";
}
