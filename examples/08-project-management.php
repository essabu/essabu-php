<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

/**
 * Initialize the SDK with your API key and tenant ID. This client will be used for
 * project management and asset management operations in this example.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id');

/**
 * Create a new project by providing name, description, startDate, endDate, and budget.
 * Returns the created project with a generated id and status ACTIVE. Throws
 * ValidationException if the endDate is before the startDate or if the name is empty.
 */
$project = $essabu->project->projects->create([
    'name' => 'Website Redesign',
    'description' => 'Complete redesign of the corporate website',
    'startDate' => '2026-04-01',
    'endDate' => '2026-07-31',
    'budget' => 25000.00,
]);
echo "Project created: {$project['id']}\n";

/**
 * Add a milestone to the project by specifying the projectId, name, and dueDate.
 * Milestones mark key checkpoints in the project timeline. Returns the created milestone
 * with a generated id. Throws ValidationException if the dueDate is outside the project's
 * date range.
 */
$milestone = $essabu->project->milestones->create([
    'projectId' => $project['id'],
    'name' => 'Design Phase Complete',
    'dueDate' => '2026-05-15',
]);
echo "Milestone: {$milestone['name']}\n";

/**
 * Create a task within a project, optionally linked to a milestone. Pass projectId,
 * milestoneId, title, description, priority (low, medium, high), and estimatedHours.
 * Returns the created task with a generated id in TODO status. Throws NotFoundException
 * if the projectId or milestoneId is invalid.
 */
$task = $essabu->project->tasks->create([
    'projectId' => $project['id'],
    'milestoneId' => $milestone['id'],
    'title' => 'Create wireframes',
    'description' => 'Design wireframes for all pages',
    'priority' => 'high',
    'estimatedHours' => 40,
]);
echo "Task created: {$task['id']}\n";

/**
 * Assign a task to a team member by passing the userId. This sets the task's assignee and
 * changes its status to IN_PROGRESS. Returns the updated task. Throws NotFoundException
 * if the userId is not a member of the project.
 */
$essabu->project->tasks->assign($task['id'], [
    'userId' => 'user-designer-01',
]);
echo "Task assigned.\n";

/**
 * Log time spent on a task. Pass the number of hours, the date, and an optional description
 * of the work done. This contributes to the project's burndown report. Returns the created
 * time entry. Throws ValidationException if hours is zero or negative.
 */
$essabu->project->tasks->logTime($task['id'], [
    'hours' => 8,
    'date' => '2026-04-02',
    'description' => 'Initial wireframe drafts',
]);
echo "Time logged.\n";

/**
 * Mark a task as complete. This changes the task status to DONE and records the completion
 * date. Returns the updated task. Throws ValidationException if the task is already completed.
 */
$essabu->project->tasks->complete($task['id']);
echo "Task completed.\n";

/**
 * Mark a milestone as complete. This records the actual completion date and updates the
 * project timeline. Returns the updated milestone. Throws ValidationException if not all
 * tasks linked to the milestone are completed.
 */
$essabu->project->milestones->complete($milestone['id']);
echo "Milestone completed.\n";

/**
 * Add a comment to a task. Pass the taskId and the comment content as a string. Comments
 * support plain text. Returns the created comment with a generated id and timestamp.
 * Throws NotFoundException if the taskId is invalid.
 */
$essabu->project->taskComments->create([
    'taskId' => $task['id'],
    'content' => 'Wireframes approved by the client.',
]);

/**
 * Retrieve the project timeline showing all milestones, tasks, and events in chronological
 * order. Returns an array with an events key containing the timeline entries. Throws
 * NotFoundException if the project ID is invalid.
 */
$timeline = $essabu->project->projects->getTimeline($project['id']);
echo "Timeline events: " . count($timeline['events'] ?? []) . "\n";

/**
 * Register a new fixed asset in the Asset module. Pass name, category, purchaseDate,
 * purchasePrice, and serialNumber. Returns the created asset with a generated id. Throws
 * ValidationException if required fields are missing or the serial number is a duplicate.
 */
$asset = $essabu->asset->assets->create([
    'name' => 'MacBook Pro 16"',
    'category' => 'equipment',
    'purchaseDate' => '2026-03-01',
    'purchasePrice' => 2499.00,
    'serialNumber' => 'SN-MBP-2026-001',
]);
echo "Asset registered: {$asset['id']}\n";

/**
 * Assign an asset to an employee by passing the employeeId and assignedDate. This records
 * the custody transfer and updates the asset's current holder. Returns the updated asset.
 * Throws NotFoundException if the asset or employee ID is invalid.
 */
$essabu->asset->assets->assignTo($asset['id'], [
    'employeeId' => 'emp-001',
    'assignedDate' => '2026-03-15',
]);
echo "Asset assigned.\n";

/**
 * Schedule recurring maintenance for an asset. Pass the assetId, frequency (e.g., quarterly,
 * monthly), next scheduled date, and a description. Returns the created schedule with a
 * generated id. Throws ValidationException if the nextDate is in the past.
 */
$schedule = $essabu->asset->maintenanceSchedules->create([
    'assetId' => $asset['id'],
    'frequency' => 'quarterly',
    'nextDate' => '2026-06-01',
    'description' => 'Routine cleaning and diagnostics',
]);
echo "Maintenance scheduled: {$schedule['id']}\n";
