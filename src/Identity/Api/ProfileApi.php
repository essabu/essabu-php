<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class ProfileApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/profile';
    }
    /**
     * @return array<string, mixed>
     */
    public function me(): array
    {
        return $this->httpClient->get($this->basePath() . "/me");
    }

    /**
     * @return array<string, mixed>
     */
    public function updateMe(array $data): array
    {
        return $this->httpClient->patch($this->basePath() . "/me", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function changePassword(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/change-password", $data);
    }

}
