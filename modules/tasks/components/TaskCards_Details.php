<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Manila');

$taskMonitoringTaskID = (int)($_POST['TaskID'] ?? 0);
$taskMonitoringTitle = trim((string)($_POST['TaskTitle'] ?? 'Untitled task'));
$taskMonitoringDescription = trim((string)($_POST['TaskDescription'] ?? 'No description was provided.'));
$taskMonitoringStatus = strtolower(trim((string)($_POST['DisplayStatus'] ?? 'pending')));
$taskMonitoringDueDateValue = trim((string)($_POST['DueDate'] ?? ''));
$taskMonitoringCategory = trim((string)($_POST['Category'] ?? 'Uncategorized'));
$taskMonitoringPriority = ucfirst(strtolower(trim((string)($_POST['Priority'] ?? 'Medium'))));
$taskMonitoringEstimate = trim((string)($_POST['Estimate'] ?? 'Not set'));

$taskMonitoringAllowedStatuses = ['pending', 'ongoing', 'complete', 'late'];

if (!in_array($taskMonitoringStatus, $taskMonitoringAllowedStatuses, true)) {
    $taskMonitoringStatus = 'pending';
}

$taskMonitoringStatusLabels = [
    'pending' => 'Pending',
    'ongoing' => 'Ongoing',
    'complete' => 'Complete',
    'late' => 'Late',
];

$taskMonitoringDueDateLabel = 'No due date';

if ($taskMonitoringDueDateValue !== '') {
    $taskMonitoringDueDate = DateTimeImmutable::createFromFormat('!Y-m-d', $taskMonitoringDueDateValue);

    if ($taskMonitoringDueDate instanceof DateTimeImmutable) {
        $taskMonitoringDueDateLabel = $taskMonitoringDueDate->format('F j, Y');
    }
}
?>
<section
    class="task-monitoring-modal task-monitoring-details-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="taskMonitoringDetailsTitle"
    data-task-details-dialog
>
    <header class="task-monitoring-modal-header task-monitoring-details-header">
        <div>
            <span class="task-monitoring-details-status is-<?php echo htmlspecialchars($taskMonitoringStatus, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($taskMonitoringStatusLabels[$taskMonitoringStatus], ENT_QUOTES, 'UTF-8'); ?>
            </span>
            <h2 id="taskMonitoringDetailsTitle">
                <?php echo htmlspecialchars($taskMonitoringTitle, ENT_QUOTES, 'UTF-8'); ?>
            </h2>
            <p>Task #<?php echo $taskMonitoringTaskID; ?></p>
        </div>
        <button
            class="task-monitoring-modal-close"
            type="button"
            aria-label="Close task details"
            data-task-details-close
        >×</button>
    </header>

    <div class="task-monitoring-details-body">
        <section class="task-monitoring-details-description" aria-labelledby="taskMonitoringDetailsDescriptionTitle">
            <h3 id="taskMonitoringDetailsDescriptionTitle">Description</h3>
            <p><?php echo nl2br(htmlspecialchars($taskMonitoringDescription, ENT_QUOTES, 'UTF-8')); ?></p>
        </section>

        <div class="task-monitoring-details-grid">
            <article class="task-monitoring-details-item">
                <span>Due date</span>
                <strong><?php echo htmlspecialchars($taskMonitoringDueDateLabel, ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article class="task-monitoring-details-item">
                <span>Priority</span>
                <strong class="task-monitoring-details-priority is-<?php echo strtolower(htmlspecialchars($taskMonitoringPriority, ENT_QUOTES, 'UTF-8')); ?>">
                    <?php echo htmlspecialchars($taskMonitoringPriority, ENT_QUOTES, 'UTF-8'); ?>
                </strong>
            </article>
            <article class="task-monitoring-details-item">
                <span>Category</span>
                <strong><?php echo htmlspecialchars($taskMonitoringCategory, ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article class="task-monitoring-details-item">
                <span>Time estimate</span>
                <strong><?php echo htmlspecialchars($taskMonitoringEstimate, ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article class="task-monitoring-details-item">
                <span>Assigned to</span>
                <strong class="task-monitoring-details-assignee">
                    <span class="task-monitoring-assignee-avatar">AJ</span>
                    You
                </strong>
            </article>
            <article class="task-monitoring-details-item">
                <span>Current status</span>
                <strong><?php echo htmlspecialchars($taskMonitoringStatusLabels[$taskMonitoringStatus], ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
        </div>
    </div>

    <footer class="task-monitoring-modal-footer">
        <button class="task-monitoring-secondary-button" type="button" data-task-details-close>Close</button>
        <button
            class="task-monitoring-primary-button"
            type="button"
            data-task-details-edit="<?php echo $taskMonitoringTaskID; ?>"
        >
            Edit task
        </button>
    </footer>
</section>
