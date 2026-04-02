<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class ApiKeyApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/api-keys';
    }
    /**
     * @return array<string, mixed>
     */
    public function rotate(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/rotate");
    }

    /**
     * @return array<string, mixed>
     */
    public function revoke(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/revoke");
    }

}
