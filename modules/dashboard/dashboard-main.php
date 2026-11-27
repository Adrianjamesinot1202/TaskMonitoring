<?php
declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: ../../index.php#dashboard');
    exit;
}

header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Manila');

$dashboardToday = new DateTimeImmutable('today');
?>
<header class="task-monitoring-topbar">
    <div class="task-monitoring-heading">
        <h1>Good day, AJ</h1>
        <p><?php echo $dashboardToday->format('l, F j, Y'); ?></p>
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

<section class="task-monitoring-overview" aria-label="Task overview" data-dashboard-today="<?php echo $dashboardToday->format('Y-m-d'); ?>">
    <article class="task-monitoring-metric-card is-pending">
        <div>
            <p class="task-monitoring-metric-label">Pending</p>
            <p class="task-monitoring-metric-value" data-status-metric="pending">0</p>
            <p class="task-monitoring-metric-detail" data-status-detail="pending">—</p>
        </div>
    </article>

    <article class="task-monitoring-metric-card is-ongoing">
        <div>
            <p class="task-monitoring-metric-label">Ongoing</p>
            <p class="task-monitoring-metric-value" data-status-metric="ongoing">0</p>
            <p class="task-monitoring-metric-detail" data-status-detail="ongoing">—</p>
        </div>
    </article>

    <article class="task-monitoring-metric-card is-complete">
        <div>
            <p class="task-monitoring-metric-label">Complete</p>
            <p class="task-monitoring-metric-value" data-status-metric="complete">0</p>
            <p class="task-monitoring-metric-detail" data-status-detail="complete">—</p>
        </div>
    </article>

    <article class="task-monitoring-metric-card is-late">
        <div>
            <p class="task-monitoring-metric-label">Late</p>
            <p class="task-monitoring-metric-value" data-status-metric="late">0</p>
            <p class="task-monitoring-metric-detail" data-status-detail="late">—</p>
        </div>
    </article>
</section>

<section id="taskMonitoringBoard" aria-labelledby="taskMonitoringBoardTitle">
    <div class="task-monitoring-toolbar">
        <div class="task-monitoring-section-title">
            <h2 id="taskMonitoringBoardTitle">My Dashboard</h2>
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

    <section class="task-monitoring-dashboard-widgets" aria-label="Workspace widgets">
        <section class="task-monitoring-widget" aria-labelledby="taskMonitoringProjectsTitle">
            <div id="taskMonitoringProjectsContainer" data-module-component aria-live="polite">
                <p class="task-monitoring-widget-loading">Loading projects...</p>
            </div>
        </section>
        <section class="task-monitoring-widget" aria-labelledby="taskMonitoringNotepadTitle">
            <div id="taskMonitoringNotepadContainer" data-module-component>
                <p class="task-monitoring-widget-loading">Loading notepad...</p>
            </div>
        </section>
    </section>

    <div id="taskMonitoringSummaryContainer" data-module-component aria-live="polite">
        <div class="task-monitoring-empty-state is-visible">
            <p>Loading tasks...</p>
        </div>
    </div>
</section>
