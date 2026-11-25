//===================================================================================================
// BIND EVENTS
//===================================================================================================

bindEvent('input.dashboardNotepad', '#taskMonitoringNotepad', function () {
    dashboard_notepad_save();
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
            notepad.value = window.localStorage.getItem('taskMonitoringNotepadDraft') || '';
        } catch (storageError) {
            document.getElementById('taskMonitoringNotepadStorage').textContent = 'Browser storage unavailable. Copy your notes before leaving.';
        }

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

    document.getElementById('taskMonitoringNotepadCount').textContent = notepad.value.length;

    try {
        window.localStorage.setItem('taskMonitoringNotepadDraft', notepad.value);
        storageStatus.textContent = '';
    } catch (storageError) {
        storageStatus.textContent = 'Unable to save. Copy your notes before leaving.';
    }
}
