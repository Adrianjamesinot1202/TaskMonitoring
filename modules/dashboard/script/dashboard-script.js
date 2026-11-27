//===================================================================================================
// MODULE VARIABLES
//===================================================================================================

var dashboardNotepadNotes = [];
var dashboardNotepadDraft = '';
var dashboardNotepadStorageReady = true;

//===================================================================================================
// BIND EVENTS
//===================================================================================================

bindEvent('input.dashboardNotepad', '#taskMonitoringNotepad', function () {
    dashboard_notepad_save();
});

bindEvent('click.dashboardNotepadNotes', '#btnDashboardNotepadNotes', function () {
    dashboard_notepad_view(this.getAttribute('aria-expanded') !== 'true');
});

bindEvent('click.dashboardNotepadNew', '#btnDashboardNotepadNew', function () {
    dashboard_notepad_open(-1);
});

bindEvent('click.dashboardNotepadOpen', '[data-notepad-open]', function () {
    dashboard_notepad_open(Number(this.getAttribute('data-note-index')));
});

bindEvent('keydown.dashboardNotepadBack', '#taskMonitoringNotepadContainer', function (event) {
    if (event.key === 'Escape' && document.getElementById('btnDashboardNotepadNotes').getAttribute('aria-expanded') === 'true') {
        dashboard_notepad_view(false);
        document.getElementById('btnDashboardNotepadNotes').focus();
    }
});

//===================================================================================================
// ALL FUNCTIONS RELATED TO DASHBOARD
//===================================================================================================

function dashboard_init() {
    return [dashboard_projects_summary(), dashboard_notepad_summary()];
}

function dashboard_refresh_metrics() {
    var overview = document.querySelector('[data-dashboard-today]');

    if (!overview) {
        return;
    }

    var today = overview.getAttribute('data-dashboard-today');
    var todayTimestamp = Date.parse(today + 'T00:00:00Z');
    var statusCounts = { pending: 0, ongoing: 0, complete: 0, late: 0 };
    var totalTasks = 0;
    var pendingDueToday = 0;
    var ongoingHighPriority = 0;
    var oldestOverdueDays = 0;

    document.querySelectorAll('[data-task-card]').forEach(function (taskCard) {
        var status = taskCard.getAttribute('data-status');
        var dueDate = taskCard.getAttribute('data-due-date');

        if (typeof statusCounts[status] !== 'number') {
            return;
        }

        statusCounts[status]++;
        totalTasks++;

        if (status === 'pending' && dueDate === today) {
            pendingDueToday++;
        }

        if (status === 'ongoing' && (taskCard.getAttribute('data-priority') || '').toUpperCase() === 'HIGH') {
            ongoingHighPriority++;
        }

        if (status === 'late') {
            // Compare calendar dates in UTC to avoid browser timezone/DST shifts.
            var dueTimestamp = Date.parse(dueDate + 'T00:00:00Z');

            if (Number.isFinite(dueTimestamp)) {
                oldestOverdueDays = Math.max(oldestOverdueDays, Math.floor((todayTimestamp - dueTimestamp) / 86400000));
            }
        }
    });

    Object.keys(statusCounts).forEach(function (status) {
        overview.querySelector('[data-status-metric="' + status + '"]').textContent = statusCounts[status];
    });

    overview.querySelector('[data-status-detail="pending"]').textContent = pendingDueToday + ' due today';
    overview.querySelector('[data-status-detail="ongoing"]').textContent = ongoingHighPriority + ' high priority';
    overview.querySelector('[data-status-detail="complete"]').textContent = (totalTasks === 0 ? 0 : Math.round(statusCounts.complete / totalTasks * 100)) + '% of all tasks';
    overview.querySelector('[data-status-detail="late"]').textContent = statusCounts.late === 0
        ? 'No overdue tasks'
        : 'Oldest: ' + oldestOverdueDays + (oldestOverdueDays === 1 ? ' day overdue' : ' days overdue');
}

function dashboard_projects_summary() {
    return $.post('modules/dashboard/components/DashboardProjects_Summary.php', {}, function (html) {
        $('#taskMonitoringProjectsContainer').html(html);
    }).fail(function (xhr, status, error) {
        if (status === 'abort') {
            return;
        }

        $('#taskMonitoringProjectsContainer').text('Unable to load projects. Please refresh to try again.');

        if (typeof window.handleAjaxError === 'function') {
            window.handleAjaxError(xhr, status, error);
        }
    }).always(function () {
        app_module_component_ready('taskMonitoringProjectsContainer');
    });
}

function dashboard_notepad_summary() {
    return $.post('modules/dashboard/components/DashboardNotepad_Summary.php', {}, function (html) {
        $('#taskMonitoringNotepadContainer').html(html);

        var notepad = document.getElementById('taskMonitoringNotepad');

        try {
            // Keep the original draft key so notes written before the list was added survive.
            dashboardNotepadDraft = window.localStorage.getItem('taskMonitoringNotepadDraft') || '';
            var savedNotes = JSON.parse(window.localStorage.getItem('taskMonitoringNotepadNotes') || '[]');

            if (!Array.isArray(savedNotes) || !savedNotes.every(function (note) { return typeof note === 'string'; })) {
                throw new Error('Invalid browser notes');
            }

            dashboardNotepadNotes = savedNotes;
            dashboardNotepadStorageReady = true;
        } catch (storageError) {
            dashboardNotepadStorageReady = false;
            document.getElementById('taskMonitoringNotepadStorage').textContent = 'Browser storage unavailable. Copy your notes before leaving.';
        }

        var noteIndex = dashboardNotepadDraft.trim() ? dashboardNotepadNotes.indexOf(dashboardNotepadDraft) : -1;

        if (dashboardNotepadDraft.trim() && noteIndex === -1) {
            noteIndex = dashboardNotepadNotes.push(dashboardNotepadDraft) - 1;
        }

        notepad.value = dashboardNotepadDraft;
        notepad.setAttribute('data-note-index', noteIndex);
        document.getElementById('taskMonitoringNotepadCount').textContent = notepad.value.length;
    }).fail(function (xhr, status, error) {
        if (status === 'abort') {
            return;
        }

        $('#taskMonitoringNotepadContainer').text('Unable to load notepad. Please refresh to try again.');

        if (typeof window.handleAjaxError === 'function') {
            window.handleAjaxError(xhr, status, error);
        }
    }).always(function () {
        app_module_component_ready('taskMonitoringNotepadContainer');
    });
}

function dashboard_notepad_save() {
    var notepad = document.getElementById('taskMonitoringNotepad');
    var storageStatus = document.getElementById('taskMonitoringNotepadStorage');
    var noteIndex = Number(notepad.getAttribute('data-note-index'));

    dashboardNotepadDraft = notepad.value;
    document.getElementById('taskMonitoringNotepadCount').textContent = notepad.value.length;

    if (noteIndex >= 0 && noteIndex < dashboardNotepadNotes.length) {
        dashboardNotepadNotes[noteIndex] = notepad.value;
    } else if (notepad.value.trim()) {
        notepad.setAttribute('data-note-index', dashboardNotepadNotes.push(notepad.value) - 1);
    }

    if (!dashboardNotepadStorageReady) {
        storageStatus.textContent = 'Browser storage unavailable. Copy your notes before leaving.';
        return;
    }

    try {
        window.localStorage.setItem('taskMonitoringNotepadNotes', JSON.stringify(dashboardNotepadNotes));
        window.localStorage.setItem('taskMonitoringNotepadDraft', notepad.value);
        storageStatus.textContent = '';
    } catch (storageError) {
        storageStatus.textContent = 'Unable to save. Copy your notes before leaving.';
    }
}

function dashboard_notepad_view(showNotes) {
    var container = document.getElementById('taskMonitoringNotepadContainer');
    var toggle = document.getElementById('btnDashboardNotepadNotes');
    var editor = document.getElementById('taskMonitoringNotepadEditor');
    var notes = document.getElementById('taskMonitoringNotepadNotes');

    if (showNotes) {
        var list = document.getElementById('taskMonitoringNotepadList');
        var template = document.getElementById('taskMonitoringNotepadItemTemplate');
        var noteCount = 0;

        list.replaceChildren();

        dashboardNotepadNotes.forEach(function (note, noteIndex) {
            if (!note.trim()) {
                return;
            }

            var item = template.content.cloneNode(true);
            var lines = note.trim().split(/\r?\n/);
            var title = lines.shift();
            var preview = lines.join(' ').trim();

            item.querySelector('[data-notepad-open]').setAttribute('data-note-index', noteIndex);
            item.querySelector('[data-notepad-title]').textContent = title;
            item.querySelector('[data-notepad-preview]').textContent = preview;
            item.querySelector('[data-notepad-preview]').hidden = !preview;
            list.appendChild(item);
            noteCount++;
        });

        document.getElementById('taskMonitoringNotepadTotal').textContent = noteCount;
        document.getElementById('taskMonitoringNotepadEmpty').hidden = noteCount !== 0;
        list.hidden = noteCount === 0;
    }

    container.classList.toggle('is-notes-view', showNotes);
    toggle.setAttribute('aria-expanded', showNotes ? 'true' : 'false');
    toggle.setAttribute('aria-label', showNotes ? 'Back to notepad' : 'View notes');
    toggle.setAttribute('title', showNotes ? 'Back to notepad' : 'View notes');
    toggle.querySelector('[data-notepad-list-icon]').toggleAttribute('hidden', showNotes);
    toggle.querySelector('[data-notepad-back-icon]').toggleAttribute('hidden', !showNotes);
    editor.setAttribute('aria-hidden', showNotes ? 'true' : 'false');
    editor.toggleAttribute('inert', showNotes);
    notes.setAttribute('aria-hidden', showNotes ? 'false' : 'true');
    notes.toggleAttribute('inert', !showNotes);
}

function dashboard_notepad_open(noteIndex) {
    var notepad = document.getElementById('taskMonitoringNotepad');

    if (!Number.isInteger(noteIndex) || noteIndex < -1 || noteIndex >= dashboardNotepadNotes.length) {
        return;
    }

    notepad.value = noteIndex === -1 ? '' : dashboardNotepadNotes[noteIndex];
    notepad.setAttribute('data-note-index', noteIndex);
    dashboard_notepad_save();
    dashboard_notepad_view(false);
    notepad.focus({ preventScroll: true });
}
