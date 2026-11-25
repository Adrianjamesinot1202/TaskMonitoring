<?php
declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: ../../index.php#tasks');
    exit;
}

header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Manila');

$tasksToday = new DateTimeImmutable('today');
?>
<header class="task-monitoring-topbar">
    <div class="task-monitoring-heading">
        <h1>Tasks</h1>
        <p><?php echo $tasksToday->format('l, F j, Y'); ?></p>
    </div>
    <div class="task-monitoring-topbar-actions">
        <button class="task-monitoring-icon-button" type="button" aria-label="Notifications" data-prototype-action="Notifications">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                <path d="M10 21h4"></path>
            </svg>
            <span class="task-monitoring-notification-dot" aria-hidden="true"></span>
        </button>
        <span class="task-monitoring-user-avatar" role="img" aria-label="User profile: AJ" title="AJ">AJ</span>
    </div>
</header>

<section id="taskMonitoringBoard" aria-labelledby="taskMonitoringBoardTitle">
    <div class="task-monitoring-toolbar">
        <div class="task-monitoring-section-title">
            <h2 id="taskMonitoringBoardTitle">My task board</h2>
        </div>
        <div class="task-monitoring-toolbar-actions">
            <label class="task-monitoring-search" for="taskMonitoringSearch">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="M20 20l-4-4"></path>
                </svg>
                <input id="taskMonitoringSearch" type="search" placeholder="Search tasks" aria-label="Search tasks" autocomplete="off">
            </label>
            <button class="task-monitoring-primary-button" id="btnTaskMonitoringAdd" type="button" aria-label="Add task">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
                <span>Add Task</span>
            </button>
        </div>
    </div>

    <div id="taskMonitoringSummaryContainer" data-module-component aria-live="polite">
        <div class="task-monitoring-empty-state is-visible">
            <p>Loading tasks...</p>
        </div>
    </div>
</section>
