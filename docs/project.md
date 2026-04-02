# Project Module Reference

The Project module provides project management capabilities including projects, tasks, task comments, milestones, resource allocations, and reporting.

## Client Access

```php
$essabu = new EssabuClient('your-api-key');
$project = $essabu->project;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `ProjectApi` | `$project->projects` | Project CRUD |
| `TaskApi` | `$project->tasks` | Task management |
| `TaskCommentApi` | `$project->taskComments` | Task comments (separate API) |
| `MilestoneApi` | `$project->milestones` | Project milestones |
| `ResourceAllocationApi` | `$project->resourceAllocations` | Resource allocation |
| `ReportApi` | `$project->reports` | Project reports |

---

## Base CRUD Methods (inherited by all APIs)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `list(?PageRequest) -> PageResponse` | `GET /api/{basePath}` | List with pagination |
| `get(string $id) -> array` | `GET /api/{basePath}/{id}` | Get by ID |
| `create(array $data) -> array` | `POST /api/{basePath}` | Create resource |
| `update(string $id, array $data) -> array` | `PATCH /api/{basePath}/{id}` | Update resource |
| `delete(string $id) -> array` | `DELETE /api/{basePath}/{id}` | Delete resource |

## Standard CRUD-Only APIs

| Class | Base Path |
|-------|-----------|
| `ProjectApi` | `project/projects` |
| `TaskApi` | `project/tasks` |
| `TaskCommentApi` | `project/task-comments` |
| `MilestoneApi` | `project/milestones` |
| `ResourceAllocationApi` | `project/resource-allocations` |
| `ReportApi` | `project/reports` |

```php
// Create a project
$proj = $project->projects->create([
    'name' => 'Website Redesign',
    'startDate' => '2026-04-01',
    'endDate' => '2026-06-30',
    'budget' => 50000,
]);

// Create a task
$task = $project->tasks->create([
    'projectId' => $projectId,
    'title' => 'Design mockups',
    'assigneeId' => $userId,
]);

// Add a comment
$comment = $project->taskComments->create([
    'taskId' => $taskId,
    'content' => 'Looking good!',
]);

// Create a milestone
$milestone = $project->milestones->create([
    'projectId' => $projectId,
    'name' => 'Phase 1 Complete',
    'dueDate' => '2026-04-30',
]);

// Allocate resources
$allocation = $project->resourceAllocations->create([
    'projectId' => $projectId,
    'userId' => $userId,
    'hoursPerWeek' => 20,
    'startDate' => '2026-04-01',
]);

// Get report
$report = $project->reports->list();
```

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request data (missing project name, invalid dates) |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | Project, task, or milestone not found |
| `409` | Conflict (duplicate resource allocation) |
| `422` | Business rule violation (end date before start date) |
