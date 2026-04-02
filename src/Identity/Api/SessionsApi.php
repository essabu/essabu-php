<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class SessionsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(array $params = []): array
    {
        return $this->http->get('/sessions', $params);
    }

    /** @return array<string, mixed> */
    public function get(string $sessionId): array
    {
        return $this->http->get("/sessions/{$sessionId}");
    }

    /** @return array<string, mixed> */
    public function revoke(string $sessionId): array
    {
        return $this->http->deleteWithResponse("/sessions/{$sessionId}");
    }

    /** @return array<string, mixed> */
    public function revokeAll(): array
    {
        return $this->http->post('/sessions/revoke-all');
    }
}
