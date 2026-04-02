<?php

declare(strict_types=1);

namespace Essabu\Project;

use Essabu\Common\HttpClient;
use Essabu\Project\Api\ProjectsApi;
use Essabu\Project\Api\MilestonesApi;
use Essabu\Project\Api\TasksApi;
use Essabu\Project\Api\TaskCommentsApi;
use Essabu\Project\Api\ResourceAllocationsApi;
use Essabu\Project\Api\ReportsApi;

/**
 * Project module client.
 *
 * @property-read ProjectsApi $projects
 * @property-read MilestonesApi $milestones
 * @property-read TasksApi $tasks
 * @property-read TaskCommentsApi $taskComments
 * @property-read ResourceAllocationsApi $resourceAllocations
 * @property-read ReportsApi $reports
 */
final class ProjectClient
{
    /** @var array<string, object> */
    private array $cache = [];

    public function __construct(
        private readonly HttpClient $http,
    ) {
    }

    public function __get(string $name): object
    {
        return match ($name) {
            'projects' => $this->resolve($name, ProjectsApi::class),
            'milestones' => $this->resolve($name, MilestonesApi::class),
            'tasks' => $this->resolve($name, TasksApi::class),
            'taskComments' => $this->resolve($name, TaskCommentsApi::class),
            'resourceAllocations' => $this->resolve($name, ResourceAllocationsApi::class),
            'reports' => $this->resolve($name, ReportsApi::class),
            default => throw new \InvalidArgumentException("Unknown Project API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
