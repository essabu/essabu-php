<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * Abstract CRUD API base class for simple resource endpoints.
 */
abstract class CrudApi extends BaseApi
{
    protected string $basePath = '';

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->http->get($this->basePath, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->http->get("{$this->basePath}/{$id}");
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post($this->basePath, $data);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->patch("{$this->basePath}/{$id}", $data);
    }

    public function delete(string $id): void
    {
        $this->http->delete("{$this->basePath}/{$id}");
    }
}
