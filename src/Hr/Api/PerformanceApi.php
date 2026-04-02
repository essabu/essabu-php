<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing performance resources.
 */
final class PerformanceApi extends BaseApi
{
    private const CYCLES_PATH = '/api/hr/review-cycles';
    private const REVIEWS_PATH = '/api/hr/performance-reviews';
    private const GOALS_PATH = '/api/hr/goals';

    // --- Review Cycles ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createCycle(array $request): array
    {
        return $this->http->post(self::CYCLES_PATH, $request);
    }

    public function listCycles(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::CYCLES_PATH, $page));
        return PageResponse::fromArray($data);
    }

    // --- Reviews ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createReview(array $request): array
    {
        return $this->http->post(self::REVIEWS_PATH, $request);
    }

    /** @return array<string, mixed> */
    public function getReview(string $id): array
    {
        return $this->http->get(self::REVIEWS_PATH . '/' . $id);
    }

    /** @return array<string, mixed> */
    public function submitReview(string $id): array
    {
        return $this->http->put(self::REVIEWS_PATH . '/' . $id . '/submit');
    }

    /** @return array<string, mixed> */
    public function acknowledgeReview(string $id): array
    {
        return $this->http->put(self::REVIEWS_PATH . '/' . $id . '/acknowledge');
    }

    // --- Goals ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createGoal(array $request): array
    {
        return $this->http->post(self::GOALS_PATH, $request);
    }

    public function listGoals(string $employeeId, ?PageRequest $page = null): PageResponse
    {
        $path = $this->withPagination(self::GOALS_PATH . '?employeeId=' . $employeeId, $page);
        $data = $this->http->get($path);
        return PageResponse::fromArray($data);
    }

    /** @return array<string, mixed> */
    public function updateProgress(string $id, int $progress): array
    {
        return $this->http->put(self::GOALS_PATH . '/' . $id . '/progress', ['progress' => $progress]);
    }
}
