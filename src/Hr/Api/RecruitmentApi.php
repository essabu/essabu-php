<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing recruitment resources.
 */
final class RecruitmentApi extends BaseApi
{
    private const JOBS_PATH = '/api/hr/job-postings';
    private const APPS_PATH = '/api/hr/applications';
    private const INTERVIEWS_PATH = '/api/hr/interviews';

    // --- Job Postings ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createJobPosting(array $request): array
    {
        return $this->http->post(self::JOBS_PATH, $request);
    }

    /** @return array<string, mixed> */
    public function getJobPosting(string $id): array
    {
        return $this->http->get(self::JOBS_PATH . '/' . $id);
    }

    public function listJobPostings(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::JOBS_PATH, $page));
        return PageResponse::fromArray($data);
    }

    /** @return array<string, mixed> */
    public function publishJobPosting(string $id): array
    {
        return $this->http->put(self::JOBS_PATH . '/' . $id . '/publish');
    }

    /** @return array<string, mixed> */
    public function closeJobPosting(string $id): array
    {
        return $this->http->put(self::JOBS_PATH . '/' . $id . '/close');
    }

    // --- Applications ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createApplication(array $request): array
    {
        return $this->http->post(self::APPS_PATH, $request);
    }

    /** @return array<string, mixed> */
    public function getApplication(string $id): array
    {
        return $this->http->get(self::APPS_PATH . '/' . $id);
    }

    public function listApplications(string $jobPostingId, ?PageRequest $page = null): PageResponse
    {
        $path = $this->withPagination(self::APPS_PATH . '?jobPostingId=' . $jobPostingId, $page);
        $data = $this->http->get($path);
        return PageResponse::fromArray($data);
    }

    /** @return array<string, mixed> */
    public function advanceApplication(string $id): array
    {
        return $this->http->put(self::APPS_PATH . '/' . $id . '/advance');
    }

    /** @return array<string, mixed> */
    public function rejectApplication(string $id): array
    {
        return $this->http->put(self::APPS_PATH . '/' . $id . '/reject');
    }

    /** @return array<string, mixed> */
    public function hireApplication(string $id): array
    {
        return $this->http->put(self::APPS_PATH . '/' . $id . '/hire');
    }

    // --- Interviews ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function scheduleInterview(array $request): array
    {
        return $this->http->post(self::INTERVIEWS_PATH, $request);
    }

    /** @return array<string, mixed> */
    public function getInterview(string $id): array
    {
        return $this->http->get(self::INTERVIEWS_PATH . '/' . $id);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function completeInterview(string $id, array $request): array
    {
        return $this->http->put(self::INTERVIEWS_PATH . '/' . $id . '/complete', $request);
    }
}
