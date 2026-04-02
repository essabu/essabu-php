<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing onboarding resources.
 */
final class OnboardingApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/onboarding-plans';

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

    /** @return array<string, mixed> */
    public function completeTask(string $taskId): array
    {
        return $this->http->put('/api/hr/onboarding-tasks/' . $taskId . '/complete');
    }

    /** @return array<string, mixed> */
    public function getProgress(string $planId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $planId . '/progress');
    }
}
