<?php

declare(strict_types=1);

namespace Essabu\Project;

use Essabu\Common\HttpClient;
use Essabu\Project\Api\MilestoneApi;
use Essabu\Project\Api\ProjectApi;
use Essabu\Project\Api\ReportApi;
use Essabu\Project\Api\ResourceAllocationApi;
use Essabu\Project\Api\TaskApi;
use Essabu\Project\Api\TaskCommentApi;

/**
 * @property-read ProjectApi $projects
 * @property-read TaskApi $tasks
 * @property-read MilestoneApi $milestones
 * @property-read ResourceAllocationApi $resourceAllocations
 * @property-read TaskCommentApi $taskComments
 * @property-read ReportApi $reports
 */
final class ProjectClient
{
    /** @var array<string, object> */
    private array $instances = [];

    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function __get(string $name): object
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $this->instances[$name] = match ($name) {
            'projects' => new ProjectApi($this->httpClient),
            'tasks' => new TaskApi($this->httpClient),
            'milestones' => new MilestoneApi($this->httpClient),
            'resourceAllocations' => new ResourceAllocationApi($this->httpClient),
            'taskComments' => new TaskCommentApi($this->httpClient),
            'reports' => new ReportApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Project API: {$name}"),
        };

        return $this->instances[$name];
    }
}
