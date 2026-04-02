<?php

/**
 * Essabu PHP SDK - Authentication Example
 *
 * This example demonstrates the full authentication lifecycle:
 * register a user, login, use the token, refresh it,
 * and manage sessions via the Identity module.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/authentication.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;
use Essabu\Common\Exception\UnauthorizedException;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\Exception\EssabuException;

// 1. Initialize the client with an API key (for initial operations)
$essabu = new Essabu(
    apiKey: getenv('ESSABU_API_KEY') ?: 'sk_test_your_api_key',
    tenantId: getenv('ESSABU_TENANT_ID') ?: 'tenant_your_id',
);

try {
    // 2. Register a new user
    $registration = $essabu->identity->auth->register([
        'email'     => 'alice@example.com',
        'password'  => 'SecureP@ssw0rd!',
        'firstName' => 'Alice',
        'lastName'  => 'Mutombo',
    ]);
    echo "User registered!\n";
    echo "  User ID: {$registration['userId']}\n";
    echo "  Verification email sent to: {$registration['email']}\n";

    // 3. Verify email (using the token from the verification email)
    $verified = $essabu->identity->auth->verifyEmail('verification_token_from_email');
    echo "\nEmail verified: {$verified['verified']}\n";

    // 4. Login to get access and refresh tokens
    $loginResult = $essabu->identity->auth->login([
        'email'    => 'alice@example.com',
        'password' => 'SecureP@ssw0rd!',
    ]);
    echo "\nLogin successful!\n";
    echo "  Access token:  " . substr($loginResult['accessToken'], 0, 20) . "...\n";
    echo "  Refresh token: " . substr($loginResult['refreshToken'], 0, 20) . "...\n";
    echo "  Expires in:    {$loginResult['expiresIn']} seconds\n";

    // 5. Use the token across modules
    //    Create a new client authenticated with the user's token
    $userClient = new Essabu(
        apiKey: $loginResult['accessToken'],
        tenantId: getenv('ESSABU_TENANT_ID') ?: 'tenant_your_id',
    );

    // Now use any module with the user's identity
    $profile = $userClient->identity->profiles->get('me');
    echo "\nUser profile:\n";
    echo "  Name:  {$profile['firstName']} {$profile['lastName']}\n";
    echo "  Email: {$profile['email']}\n";
    echo "  Role:  {$profile['role']}\n";

    // Access HR data as the authenticated user
    $myLeaves = $userClient->hr->leaves->list(['page' => 0, 'size' => 5]);
    echo "\nMy leave requests: " . count($myLeaves) . " found\n";

    // 6. Refresh the token before it expires
    $refreshed = $essabu->identity->auth->refresh($loginResult['refreshToken']);
    echo "\nToken refreshed!\n";
    echo "  New access token: " . substr($refreshed['accessToken'], 0, 20) . "...\n";

    // 7. List active sessions
    $sessions = $userClient->identity->sessions->list(['page' => 0, 'size' => 10]);
    echo "\nActive sessions:\n";
    foreach ($sessions as $session) {
        echo "  - {$session['device']} from {$session['ipAddress']} ({$session['lastActive']})\n";
    }

    // 8. Enable two-factor authentication
    $twoFa = $userClient->identity->auth->enable2fa();
    echo "\n2FA setup:\n";
    echo "  Secret: {$twoFa['secret']}\n";
    echo "  QR code URL: {$twoFa['qrCodeUrl']}\n";

    // 9. Logout
    $userClient->identity->auth->logout();
    echo "\nLogged out successfully.\n";

} catch (UnauthorizedException $e) {
    echo "Authentication failed: {$e->getMessage()}\n";
    exit(1);
} catch (ValidationException $e) {
    echo "Validation error: {$e->getMessage()}\n";
    foreach ($e->fieldErrors as $field => $error) {
        echo "  Field '{$field}': {$error}\n";
    }
    exit(1);
} catch (EssabuException $e) {
    echo "API error ({$e->statusCode}): {$e->getMessage()}\n";
    exit(1);
}
