<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Manila');

$taskMonitoringToday = new DateTimeImmutable('today');
$taskMonitoringTasks = [
    [
        'TaskID' => 101,
        'TaskTitle' => 'Build the task entry modal',
        'TaskDescription' => 'Finalize the fields, spacing, validation states, and action buttons for creating a task.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+1 day')->format('Y-m-d'),
        'Category' => 'UI Design',
        'Priority' => 'High',
        'Estimate' => '2h',
    ],
    [
        'TaskID' => 102,
        'TaskTitle' => 'Prepare Supabase table plan',
        'TaskDescription' => 'List the columns and indexes we will need after the interface is approved.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('+3 days')->format('Y-m-d'),
        'Category' => 'Backend',
        'Priority' => 'Medium',
        'Estimate' => '1h',
    ],
    [
        'TaskID' => 103,
        'TaskTitle' => 'Review the responsive board',
        'TaskDescription' => 'Check the column layout on desktop and the tab experience on smaller screens.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->format('Y-m-d'),
        'Category' => 'Review',
        'Priority' => 'High',
        'Estimate' => '45m',
    ],
    [
        'TaskID' => 104,
        'TaskTitle' => 'Define useful empty states',
        'TaskDescription' => 'Make each status column remain helpful even when it does not contain a task.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+2 days')->format('Y-m-d'),
        'Category' => 'Content',
        'Priority' => 'Low',
        'Estimate' => '30m',
    ],
    [
        'TaskID' => 105,
        'TaskTitle' => 'Confirm the task lifecycle',
        'TaskDescription' => 'Decide how completed tasks leave the active board while keeping their history.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('-2 days')->format('Y-m-d'),
        'Category' => 'Planning',
        'Priority' => 'High',
        'Estimate' => '20m',
    ],
    [
        'TaskID' => 106,
        'TaskTitle' => 'Review validation messages',
        'TaskDescription' => 'Make the form feedback clear, concise, and positioned beside the affected fields.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('-1 day')->format('Y-m-d'),
        'Category' => 'Quality',
        'Priority' => 'Medium',
        'Estimate' => '40m',
    ],
    [
        'TaskID' => 107,
        'TaskTitle' => 'Polish card interactions',
        'TaskDescription' => 'Review hover states, focus states, action placement, and touch target sizes.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+5 days')->format('Y-m-d'),
        'Category' => 'UI Design',
        'Priority' => 'Medium',
        'Estimate' => '1.5h',
    ],
    [
        'TaskID' => 108,
        'TaskTitle' => 'Approve the visual direction',
        'TaskDescription' => 'Choose the final colors, density, and page structure before backend development begins.',
        'TaskStatus' => 'COMPLETED',
        'DueDate' => $taskMonitoringToday->modify('-1 day')->format('Y-m-d'),
        'Category' => 'Review',
        'Priority' => 'Medium',
        'Estimate' => '30m',
    ],
    [
        'TaskID' => 109,
        'TaskTitle' => 'Plan the first database migration',
        'TaskDescription' => 'Turn the approved task fields into a clean Supabase migration checklist.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('+4 days')->format('Y-m-d'),
        'Category' => 'Backend',
        'Priority' => 'Medium',
        'Estimate' => '1h',
    ],
    [
        'TaskID' => 110,
        'TaskTitle' => 'Draft the filter interactions',
        'TaskDescription' => 'Define how priority, category, and due-date filters work together.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('+6 days')->format('Y-m-d'),
        'Category' => 'Planning',
        'Priority' => 'Low',
        'Estimate' => '35m',
    ],
    [
        'TaskID' => 111,
        'TaskTitle' => 'Test keyboard card controls',
        'TaskDescription' => 'Make sure every card action can be reached without using a mouse.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+3 days')->format('Y-m-d'),
        'Category' => 'Quality',
        'Priority' => 'High',
        'Estimate' => '50m',
    ],
    [
        'TaskID' => 112,
        'TaskTitle' => 'Write the empty-board copy',
        'TaskDescription' => 'Prepare short messages that explain what to do when a column is empty.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+7 days')->format('Y-m-d'),
        'Category' => 'Content',
        'Priority' => 'Low',
        'Estimate' => '25m',
    ],
    [
        'TaskID' => 113,
        'TaskTitle' => 'Document the drag behavior',
        'TaskDescription' => 'Record the accepted status changes before connecting database updates.',
        'TaskStatus' => 'COMPLETED',
        'DueDate' => $taskMonitoringToday->modify('-3 days')->format('Y-m-d'),
        'Category' => 'Documentation',
        'Priority' => 'Low',
        'Estimate' => '20m',
    ],
    [
        'TaskID' => 114,
        'TaskTitle' => 'Check desktop spacing',
        'TaskDescription' => 'Verify the board remains readable at common laptop and monitor sizes.',
        'TaskStatus' => 'COMPLETED',
        'DueDate' => $taskMonitoringToday->modify('-4 days')->format('Y-m-d'),
        'Category' => 'Review',
        'Priority' => 'Medium',
        'Estimate' => '30m',
    ],
    [
        'TaskID' => 115,
        'TaskTitle' => 'Resolve overdue content review',
        'TaskDescription' => 'Finalize the remaining labels so implementation can continue cleanly.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('-4 days')->format('Y-m-d'),
        'Category' => 'Content',
        'Priority' => 'High',
        'Estimate' => '45m',
    ],
    [
        'TaskID' => 116,
        'TaskTitle' => 'Update the old task estimates',
        'TaskDescription' => 'Review overdue items and give each one a more realistic time estimate.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('-5 days')->format('Y-m-d'),
        'Category' => 'Planning',
        'Priority' => 'Medium',
        'Estimate' => '30m',
    ],
];

$taskMonitoringColumns = [
    'pending' => [
        'label' => 'Pending',
        'description' => 'Ready to be picked up',
    ],
    'ongoing' => [
        'label' => 'Ongoing',
        'description' => 'Currently in progress',
    ],
    'complete' => [
        'label' => 'Complete',
        'description' => 'Finished and archived',
    ],
    'late' => [
        'label' => 'Late',
        'description' => 'Detected automatically',
    ],
];

$taskMonitoringGroupedTasks = [
    'pending' => [],
    'ongoing' => [],
    'complete' => [],
    'late' => [],
];

$taskMonitoringDueTodayCount = 0;

foreach ($taskMonitoringTasks as $taskMonitoringTask) {
    $taskMonitoringDueDate = new DateTimeImmutable($taskMonitoringTask['DueDate']);

    if ($taskMonitoringTask['TaskStatus'] === 'COMPLETED') {
        $taskMonitoringDisplayStatus = 'complete';
    } elseif ($taskMonitoringDueDate < $taskMonitoringToday) {
        $taskMonitoringDisplayStatus = 'late';
    } elseif ($taskMonitoringTask['TaskStatus'] === 'ONGOING') {
        $taskMonitoringDisplayStatus = 'ongoing';
    } else {
        $taskMonitoringDisplayStatus = 'pending';
    }

    $taskMonitoringTask['DisplayStatus'] = $taskMonitoringDisplayStatus;
    $taskMonitoringGroupedTasks[$taskMonitoringDisplayStatus][] = $taskMonitoringTask;

    if (
        $taskMonitoringTask['TaskStatus'] !== 'COMPLETED'
        && $taskMonitoringDueDate == $taskMonitoringToday
    ) {
        $taskMonitoringDueTodayCount++;
    }
}

$taskMonitoringActiveCount = count($taskMonitoringGroupedTasks['pending'])
    + count($taskMonitoringGroupedTasks['ongoing'])
    + count($taskMonitoringGroupedTasks['late']);
$taskMonitoringCompletedCount = count($taskMonitoringGroupedTasks['complete']);
$taskMonitoringInitialCardLimit = 3;
?>
<div
    data-task-summary
    data-active-count="<?php echo $taskMonitoringActiveCount; ?>"
    data-due-today-count="<?php echo $taskMonitoringDueTodayCount; ?>"
    data-completed-count="<?php echo $taskMonitoringCompletedCount; ?>"
>
                <div class="task-monitoring-mobile-tabs" aria-label="Task statuses">
                    <?php foreach ($taskMonitoringColumns as $taskMonitoringStatusKey => $taskMonitoringColumn) { ?>
                        <button
                            class="task-monitoring-tab<?php echo $taskMonitoringStatusKey === 'pending' ? ' is-active' : ''; ?>"
                            type="button"
                            data-mobile-status="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                            <span><?php echo htmlspecialchars($taskMonitoringColumn['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                            (<span data-mobile-count="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"><?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]); ?></span>)
                        </button>
                    <?php } ?>
                </div>

                <div class="task-monitoring-board">
                    <?php foreach ($taskMonitoringColumns as $taskMonitoringStatusKey => $taskMonitoringColumn) { ?>
                        <section
                            class="task-monitoring-column is-<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?><?php echo $taskMonitoringStatusKey === 'pending' ? ' is-mobile-active' : ''; ?>"
                            data-task-column="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"
                            aria-labelledby="taskMonitoringColumn<?php echo ucfirst($taskMonitoringStatusKey); ?>"
                        >
                            <header class="task-monitoring-column-header">
                                <div class="task-monitoring-column-identity">
                                    <span class="task-monitoring-status-dot" aria-hidden="true"></span>
                                    <div>
                                        <h3 class="task-monitoring-column-title" id="taskMonitoringColumn<?php echo ucfirst($taskMonitoringStatusKey); ?>">
                                            <?php echo htmlspecialchars($taskMonitoringColumn['label'], ENT_QUOTES, 'UTF-8'); ?>
                                        </h3>
                                        <p class="task-monitoring-column-description">
                                            <?php echo htmlspecialchars($taskMonitoringColumn['description'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="task-monitoring-column-count" data-column-count="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]); ?>
                                </span>
                            </header>

                            <div
                                class="task-monitoring-card-list"
                                data-task-drop-zone="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"
                                data-drop-enabled="<?php echo $taskMonitoringStatusKey === 'late' ? 'false' : 'true'; ?>"
                            >
                                <?php foreach ($taskMonitoringGroupedTasks[$taskMonitoringStatusKey] as $taskMonitoringTaskIndex => $taskMonitoringTask) { ?>
                                    <?php
                                    $taskMonitoringCardDueDate = new DateTimeImmutable($taskMonitoringTask['DueDate']);

                                    if ($taskMonitoringTask['DisplayStatus'] === 'complete') {
                                        $taskMonitoringDueLabel = 'Completed';
                                    } elseif ($taskMonitoringCardDueDate == $taskMonitoringToday) {
                                        $taskMonitoringDueLabel = 'Due today';
                                    } elseif ($taskMonitoringCardDueDate == $taskMonitoringToday->modify('+1 day')) {
                                        $taskMonitoringDueLabel = 'Due tomorrow';
                                    } elseif ($taskMonitoringCardDueDate < $taskMonitoringToday) {
                                        $taskMonitoringLateDays = (int)$taskMonitoringCardDueDate->diff($taskMonitoringToday)->format('%a');
                                        $taskMonitoringDueLabel = $taskMonitoringLateDays . ($taskMonitoringLateDays === 1 ? ' day late' : ' days late');
                                    } else {
                                        $taskMonitoringDueLabel = 'Due ' . $taskMonitoringCardDueDate->format('M j');
                                    }
                                    ?>
                                    <article
                                        class="task-monitoring-card<?php echo $taskMonitoringTaskIndex >= $taskMonitoringInitialCardLimit ? ' task-monitoring-page-hidden' : ''; ?>"
                                        draggable="true"
                                        data-task-card
                                        data-task-id="<?php echo (int)$taskMonitoringTask['TaskID']; ?>"
                                        data-status="<?php echo htmlspecialchars($taskMonitoringTask['DisplayStatus'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-stored-status="<?php echo htmlspecialchars($taskMonitoringTask['TaskStatus'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-title="<?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-description="<?php echo htmlspecialchars($taskMonitoringTask['TaskDescription'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-due-date="<?php echo htmlspecialchars($taskMonitoringTask['DueDate'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-category="<?php echo htmlspecialchars($taskMonitoringTask['Category'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-priority="<?php echo htmlspecialchars($taskMonitoringTask['Priority'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-estimate="<?php echo htmlspecialchars($taskMonitoringTask['Estimate'], ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                        <div class="task-monitoring-card-top">
                                            <span class="task-monitoring-category">
                                                <?php echo htmlspecialchars($taskMonitoringTask['Category'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <span class="task-monitoring-card-top-actions">
                                                <button
                                                    class="task-monitoring-card-icon-button"
                                                    type="button"
                                                    aria-label="View <?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    title="View task"
                                                    data-task-view
                                                >
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6S2.5 12 2.5 12Z"></path>
                                                        <circle cx="12" cy="12" r="2.5"></circle>
                                                    </svg>
                                                </button>
                                                <button
                                                    class="task-monitoring-card-menu"
                                                    type="button"
                                                    aria-label="Edit <?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    title="Edit task"
                                                    data-task-edit
                                                >
                                                    ...
                                                </button>
                                            </span>
                                        </div>
                                        <h3><?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p class="task-monitoring-card-description">
                                            <?php echo htmlspecialchars($taskMonitoringTask['TaskDescription'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <div class="task-monitoring-card-meta">
                                            <span class="task-monitoring-meta-item is-due">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                                    <path d="M16 3v4M8 3v4M3 10h18"></path>
                                                </svg>
                                                <?php echo htmlspecialchars($taskMonitoringDueLabel, ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <span class="task-monitoring-meta-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="9"></circle>
                                                    <path d="M12 7v5l3 2"></path>
                                                </svg>
                                                <?php echo htmlspecialchars($taskMonitoringTask['Estimate'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <span class="task-monitoring-priority is-<?php echo strtolower(htmlspecialchars($taskMonitoringTask['Priority'], ENT_QUOTES, 'UTF-8')); ?>">
                                                <?php echo htmlspecialchars($taskMonitoringTask['Priority'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                        <footer class="task-monitoring-card-footer">
                                            <span class="task-monitoring-assignee">
                                                <span class="task-monitoring-assignee-avatar">AJ</span>
                                                You
                                            </span>
                                            <span class="task-monitoring-card-actions" data-task-card-actions>
                                                <?php if ($taskMonitoringTask['DisplayStatus'] === 'complete') { ?>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Reopen Task">Reopen</button>
                                                <?php } elseif ($taskMonitoringTask['DisplayStatus'] === 'late') { ?>
                                                    <button class="task-monitoring-card-action" type="button" data-task-edit>Edit due</button>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Complete Task">Complete</button>
                                                <?php } elseif ($taskMonitoringTask['TaskStatus'] === 'PENDING') { ?>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Move to Ongoing">Start</button>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Complete Task">Complete</button>
                                                <?php } else { ?>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Move to Pending">Pause</button>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Complete Task">Complete</button>
                                                <?php } ?>
                                            </span>
                                        </footer>
                                    </article>
                                <?php } ?>

                                <div class="task-monitoring-empty-state<?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]) === 0 ? ' is-visible' : ''; ?>" data-empty-state>
                                    <p>No <?php echo strtolower(htmlspecialchars($taskMonitoringColumn['label'], ENT_QUOTES, 'UTF-8')); ?> tasks to show.</p>
                                </div>
                            </div>

                            <footer class="task-monitoring-column-footer">
                                <span class="task-monitoring-result-count">
                                    Showing
                                    <strong data-visible-card-count="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo min($taskMonitoringInitialCardLimit, count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey])); ?>
                                    </strong>
                                    of
                                    <strong data-total-card-count="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]); ?>
                                    </strong>
                                </span>
                                <?php if ($taskMonitoringStatusKey === 'complete') { ?>
                                    <button class="task-monitoring-load-button" type="button" data-prototype-action="Completed history">
                                        View history
                                    </button>
                                <?php } else { ?>
                                    <button
                                        class="task-monitoring-load-button"
                                        type="button"
                                        data-task-load-more="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]) <= $taskMonitoringInitialCardLimit ? 'hidden' : ''; ?>
                                    >
                                        Load more
                                    </button>
                                <?php } ?>
                            </footer>
                        </section>
                    <?php } ?>
                </div>
</div>
