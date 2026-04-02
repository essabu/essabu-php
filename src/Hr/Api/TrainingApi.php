<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing training resources.
 */
final class TrainingApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/trainings';

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function create(array $request): array
    {
        return $this->http->post(self::BASE_PATH, $request);
    }

    /** @return array<string, mixed> */
    public function get(string $id): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $id);
    }

    public function list(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::BASE_PATH, $page));
        return PageResponse::fromArray($data);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function update(string $id, array $request): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id, $request);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function enroll(string $trainingId, array $request): array
    {
        return $this->http->post(self::BASE_PATH . '/' . $trainingId . '/enrollments', $request);
    }

    /** @return array<string, mixed> */
    public function complete(string $trainingId, string $enrollmentId): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $trainingId . '/enrollments/' . $enrollmentId . '/complete');
    }
}
