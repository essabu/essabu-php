<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class SessionApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/sessions';
    }
    /**
     * @return array<string, mixed>
     */
    public function revoke(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/revoke");
    }

    /**
     * @return array<string, mixed>
     */
    public function revokeAll(): array
    {
        return $this->httpClient->post($this->basePath() . "/revoke-all");
    }

}
