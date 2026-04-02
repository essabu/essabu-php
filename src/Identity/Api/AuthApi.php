<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class AuthApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/auth';
    }
    /**
     * @return array<string, mixed>
     */
    public function login(array $credentials): array
    {
        return $this->httpClient->post($this->basePath() . "/login", $credentials);
    }

    /**
     * @return array<string, mixed>
     */
    public function logout(): array
    {
        return $this->httpClient->post($this->basePath() . "/logout");
    }

    /**
     * @return array<string, mixed>
     */
    public function refresh(string $refreshToken): array
    {
        return $this->httpClient->post($this->basePath() . "/refresh", ["refreshToken" => $refreshToken]);
    }

    /**
     * @return array<string, mixed>
     */
    public function forgotPassword(string $email): array
    {
        return $this->httpClient->post($this->basePath() . "/forgot-password", ["email" => $email]);
    }

    /**
     * @return array<string, mixed>
     */
    public function resetPassword(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/reset-password", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function verifyEmail(string $token): array
    {
        return $this->httpClient->post($this->basePath() . "/verify-email", ["token" => $token]);
    }

}
