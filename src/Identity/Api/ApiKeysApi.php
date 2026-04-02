<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\CrudApi;

final class ApiKeysApi extends CrudApi
{
    protected string $basePath = '/api-keys';

    /** @return array<string, mixed> */
    public function revoke(string $apiKeyId): array
    {
        return $this->http->post("/api-keys/{$apiKeyId}/revoke");
    }
}
