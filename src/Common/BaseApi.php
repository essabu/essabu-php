<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

abstract class BaseApi
{
    public function __construct(
        protected readonly HttpClient $httpClient,
    ) {
    }

    abstract protected function basePath(): string;

    public function list(?PageRequest $request = null): PageResponse
    {
        $query = $request !== null ? $request->toQuery() : [];
        $data = $this->httpClient->get($this->basePath(), $query);

        return PageResponse::fromArray($data);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->httpClient->get($this->basePath() . '/' . $id);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->httpClient->post($this->basePath(), $data);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->httpClient->patch($this->basePath() . '/' . $id, $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $id): array
    {
        return $this->httpClient->delete($this->basePath() . '/' . $id);
    }
}
