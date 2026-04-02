<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Common\Exception\AuthenticationException;
use Essabu\Essabu;

/**
 * Initialize the SDK with your API key and tenant ID. This client instance will be used
 * for all identity operations below. No network call is made at construction time.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id');

/**
 * Authenticate a user by passing email and password. Returns an array containing an access
 * token, a refresh token, and the authenticated user's profile. The access token is short-lived
 * and must be refreshed periodically. Throws AuthenticationException if the credentials are
 * invalid, or RateLimitException after too many failed login attempts.
 */
try {
    $auth = $essabu->identity->auth->login([
        'email' => 'admin@company.com',
        'password' => 'secure-password',
    ]);

    echo "Token: {$auth['token']}\n";
    echo "User: {$auth['user']['email']}\n";

    /**
     * Refresh an expired access token using the refresh token from the login response.
     * Returns a new token pair (access + refresh). The old refresh token is invalidated.
     * Throws AuthenticationException if the refresh token is expired or revoked.
     */
    $newAuth = $essabu->identity->auth->refresh($auth['refreshToken']);
    echo "New token: {$newAuth['token']}\n";

    /**
     * Retrieve the currently authenticated user's profile. Returns the user's firstName,
     * lastName, email, and role information. Requires a valid access token in the SDK session.
     * Throws AuthenticationException if the token has expired.
     */
    $profile = $essabu->identity->profile->me();
    echo "Logged in as: {$profile['firstName']} {$profile['lastName']}\n";

    /**
     * Change the current user's password. Pass the currentPassword for verification and the
     * newPassword to set. The new password must meet the platform's strength requirements.
     * Throws ValidationException if the current password is wrong or the new password is too weak.
     */
    $essabu->identity->profile->changePassword([
        'currentPassword' => 'old-password',
        'newPassword' => 'new-secure-password',
    ]);

    /**
     * Log out the current user, invalidating the access and refresh tokens. After this call,
     * the tokens can no longer be used. Returns a confirmation message.
     */
    $essabu->identity->auth->logout();
    echo "Logged out successfully.\n";
} catch (AuthenticationException $e) {
    echo "Authentication failed: {$e->getMessage()}\n";
}
