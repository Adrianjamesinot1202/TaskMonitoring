<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
?>
<header class="task-monitoring-widget-header">
    <div class="task-monitoring-widget-title">
        <span class="task-monitoring-widget-icon is-notepad" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9M13 15l-4 1 1-4L19 3l3 3-9 9Z"></path>
            </svg>
        </span>
        <h2 id="taskMonitoringNotepadTitle">Notepad</h2>
    </div>
</header>
<div class="task-monitoring-notepad-body">
    <label class="task-monitoring-visually-hidden" for="taskMonitoringNotepad">Personal notes</label>
    <textarea id="taskMonitoringNotepad" maxlength="4000" placeholder="Ideas, reminders, or a quick thought..." spellcheck="true"></textarea>
    <footer class="task-monitoring-notepad-footer">
        <span id="taskMonitoringNotepadStorage" role="status"></span>
        <span><span id="taskMonitoringNotepadCount">0</span> / 4000</span>
    </footer>
</div>
