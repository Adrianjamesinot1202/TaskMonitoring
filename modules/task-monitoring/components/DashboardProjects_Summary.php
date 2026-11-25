<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
?>
<header class="task-monitoring-widget-header">
    <div class="task-monitoring-widget-title">
        <span class="task-monitoring-widget-icon is-projects" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 7V5a2 2 0 0 1 2-2h4l2 3h8a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"></path>
            </svg>
        </span>
        <h2 id="taskMonitoringProjectsTitle">Projects</h2>
    </div>
</header>
<div class="task-monitoring-projects-empty">
    <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="14" y="14" width="36" height="42" rx="6"></rect>
        <rect x="24" y="8" width="16" height="12" rx="3" fill="currentColor" stroke="none"></rect>
        <path d="M25 31h14M25 40h14M25 49h8"></path>
    </svg>
    <h3>No projects yet</h3>
    <p>Give related tasks a place of their own.</p>
    <button class="task-monitoring-secondary-button" type="button" data-prototype-action="Create project">Create project</button>
</div>
