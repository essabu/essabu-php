<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

/**
 * API client for authentication operations.
 */
final class AuthApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function login(array $credentials): array
    {
        return $this->http->post('/auth/login', $credentials);
    }

    /** @return array<string, mixed> */
    public function register(array $data): array
    {
        return $this->http->post('/auth/register', $data);
    }

    /** @return array<string, mixed> */
    public function refresh(string $refreshToken): array
    {
        return $this->http->post('/auth/refresh', ['refresh_token' => $refreshToken]);
    }

    /** @return array<string, mixed> */
    public function logout(): array
    {
        return $this->http->post('/auth/logout');
    }

    /** @return array<string, mixed> */
    public function verifyEmail(string $token): array
    {
        return $this->http->post('/auth/verify-email', ['token' => $token]);
    }

    /** @return array<string, mixed> */
    public function resetPassword(string $email): array
    {
        return $this->http->post('/auth/reset-password', ['email' => $email]);
    }

    /** @return array<string, mixed> */
    public function enable2fa(): array
    {
        return $this->http->post('/auth/2fa/enable');
    }
}
