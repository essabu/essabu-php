<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// Create a project
$project = $essabu->project->projects->create([
    'name' => 'Website Redesign',
    'description' => 'Complete redesign of the corporate website',
    'startDate' => '2026-04-01',
    'endDate' => '2026-07-31',
    'budget' => 25000.00,
]);
echo "Project created: {$project['id']}\n";

// Add a milestone
$milestone = $essabu->project->milestones->create([
    'projectId' => $project['id'],
    'name' => 'Design Phase Complete',
    'dueDate' => '2026-05-15',
]);
echo "Milestone: {$milestone['name']}\n";

// Create tasks
$task = $essabu->project->tasks->create([
    'projectId' => $project['id'],
    'milestoneId' => $milestone['id'],
    'title' => 'Create wireframes',
    'description' => 'Design wireframes for all pages',
    'priority' => 'high',
    'estimatedHours' => 40,
]);
echo "Task created: {$task['id']}\n";

// Assign task
$essabu->project->tasks->assign($task['id'], [
    'userId' => 'user-designer-01',
]);
echo "Task assigned.\n";

// Log time
$essabu->project->tasks->logTime($task['id'], [
    'hours' => 8,
    'date' => '2026-04-02',
    'description' => 'Initial wireframe drafts',
]);
echo "Time logged.\n";

// Complete the task
$essabu->project->tasks->complete($task['id']);
echo "Task completed.\n";

// Complete milestone
$essabu->project->milestones->complete($milestone['id']);
echo "Milestone completed.\n";

// Add a comment
$essabu->project->taskComments->create([
    'taskId' => $task['id'],
    'content' => 'Wireframes approved by the client.',
]);

// Get project timeline
$timeline = $essabu->project->projects->getTimeline($project['id']);
echo "Timeline events: " . count($timeline['events'] ?? []) . "\n";

// Asset management
$asset = $essabu->asset->assets->create([
    'name' => 'MacBook Pro 16"',
    'category' => 'equipment',
    'purchaseDate' => '2026-03-01',
    'purchasePrice' => 2499.00,
    'serialNumber' => 'SN-MBP-2026-001',
]);
echo "Asset registered: {$asset['id']}\n";

// Assign asset to employee
$essabu->asset->assets->assignTo($asset['id'], [
    'employeeId' => 'emp-001',
    'assignedDate' => '2026-03-15',
]);
echo "Asset assigned.\n";

// Schedule maintenance
$schedule = $essabu->asset->maintenanceSchedules->create([
    'assetId' => $asset['id'],
    'frequency' => 'quarterly',
    'nextDate' => '2026-06-01',
    'description' => 'Routine cleaning and diagnostics',
]);
echo "Maintenance scheduled: {$schedule['id']}\n";
