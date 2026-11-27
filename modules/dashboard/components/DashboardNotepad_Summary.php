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
    <button class="task-monitoring-notepad-toggle" id="btnDashboardNotepadNotes" type="button" aria-label="View notes" title="View notes" aria-expanded="false" aria-controls="taskMonitoringNotepadNotes">
        <svg data-notepad-list-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
            <path d="M5 6h14M5 12h14M5 18h14"></path>
        </svg>
        <svg data-notepad-back-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" hidden>
            <path d="m10 5-7 7 7 7M3 12h18"></path>
        </svg>
    </button>
</header>
<div class="task-monitoring-notepad-viewport" id="taskMonitoringNotepadViewport">
    <div class="task-monitoring-notepad-body task-monitoring-notepad-editor" id="taskMonitoringNotepadEditor" aria-hidden="false">
        <label class="task-monitoring-visually-hidden" for="taskMonitoringNotepad">Personal notes</label>
        <textarea id="taskMonitoringNotepad" data-note-index="-1" maxlength="4000" placeholder="Ideas, reminders, or a quick thought..." spellcheck="true"></textarea>
        <footer class="task-monitoring-notepad-footer">
            <span id="taskMonitoringNotepadStorage" role="status"></span>
            <span><span id="taskMonitoringNotepadCount">0</span> / 4000</span>
        </footer>
    </div>
    <div class="task-monitoring-notepad-body task-monitoring-notepad-notes" id="taskMonitoringNotepadNotes" aria-hidden="true" inert>
        <div class="task-monitoring-notepad-list-header">
            <h3>Your notes <span id="taskMonitoringNotepadTotal">0</span></h3>
            <button class="task-monitoring-notepad-new" id="btnDashboardNotepadNew" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg>
                New note
            </button>
        </div>
        <div class="task-monitoring-notepad-list-body">
            <p class="task-monitoring-notepad-empty" id="taskMonitoringNotepadEmpty">No notes yet</p>
            <ul class="task-monitoring-notepad-list" id="taskMonitoringNotepadList" aria-label="Existing notes" hidden></ul>
        </div>
    </div>
</div>
<template id="taskMonitoringNotepadItemTemplate">
    <li>
        <button class="task-monitoring-notepad-item" type="button" data-notepad-open>
            <span class="task-monitoring-notepad-item-copy">
                <strong data-notepad-title></strong>
                <span data-notepad-preview></span>
            </span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"></path></svg>
        </button>
    </li>
</template>
