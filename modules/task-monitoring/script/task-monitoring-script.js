var taskMonitoringToastTimer = null;
var taskMonitoringLastFocusedElement = null;
var taskMonitoringDraggedCard = null;
var taskMonitoringBindEvent = typeof window.bindEvent === 'function'
    ? window.bindEvent
    : function (eventName, selector, callback) {
        var browserEventName = eventName.split('.')[0];

        document.addEventListener(browserEventName, function (event) {
            var matchedElement = event.target.closest(selector);

            if (matchedElement) {
                if (browserEventName === 'submit') {
                    event.preventDefault();
                }

                callback.call(matchedElement, event);
            }
        });
    };

//===================================================================================================
// BIND EVENTS
//===================================================================================================

$(document).ready(function () {
    task_monitoring_summary();
});

taskMonitoringBindEvent('click.taskMonitoringAdd', '#btnTaskMonitoringAdd', function () {
    task_monitoring_open_add();
});

taskMonitoringBindEvent('click.taskMonitoringView', '[data-task-view]', function () {
    task_monitoring_view(this.closest('[data-task-card]'));
});

taskMonitoringBindEvent('click.taskMonitoringEdit', '[data-task-edit]', function () {
    task_monitoring_open_edit(this.closest('[data-task-card]'));
});

taskMonitoringBindEvent('click.taskMonitoringClose', '[data-task-modal-close]', function () {
    task_monitoring_close_modal();
});

taskMonitoringBindEvent('click.taskMonitoringBackdrop', '#taskMonitoringModal', function (event) {
    if (event.target.id === 'taskMonitoringModal') {
        task_monitoring_close_modal();
    }
});

taskMonitoringBindEvent('click.taskMonitoringDetailsClose', '[data-task-details-close]', function () {
    task_monitoring_close_details();
});

taskMonitoringBindEvent('click.taskMonitoringDetailsBackdrop', '#taskMonitoringDetailsModal', function (event) {
    if (event.target.id === 'taskMonitoringDetailsModal') {
        task_monitoring_close_details();
    }
});

taskMonitoringBindEvent('click.taskMonitoringDetailsEdit', '[data-task-details-edit]', function () {
    var taskID = this.getAttribute('data-task-details-edit');
    var taskCard = document.querySelector('[data-task-card][data-task-id="' + taskID + '"]');

    task_monitoring_close_details();
    task_monitoring_open_edit(taskCard);
});

taskMonitoringBindEvent('submit.taskMonitoringForm', '#taskMonitoringForm', function () {
    task_monitoring_preview_save();
});

taskMonitoringBindEvent('input.taskMonitoringSearch', '#taskMonitoringSearch', function () {
    task_monitoring_filter(this.value);
});

taskMonitoringBindEvent('click.taskMonitoringMobileTab', '[data-mobile-status]', function () {
    task_monitoring_change_mobile_tab(this.getAttribute('data-mobile-status'));
});

taskMonitoringBindEvent('click.taskMonitoringPrototype', '[data-prototype-action]', function () {
    task_monitoring_show_toast(this.getAttribute('data-prototype-action') + ' will be connected during the backend phase.');
});

taskMonitoringBindEvent('click.taskMonitoringLoadMore', '[data-task-load-more]', function () {
    task_monitoring_load_more(this.getAttribute('data-task-load-more'));
});

taskMonitoringBindEvent('dragstart.taskMonitoringCard', '[data-task-card]', function (event) {
    task_monitoring_drag_start(this, event);
});

taskMonitoringBindEvent('dragend.taskMonitoringCard', '[data-task-card]', function () {
    task_monitoring_drag_end();
});

taskMonitoringBindEvent('dragover.taskMonitoringColumn', '[data-task-drop-zone]', function (event) {
    task_monitoring_drag_over(this, event);
});

taskMonitoringBindEvent('dragleave.taskMonitoringColumn', '[data-task-drop-zone]', function (event) {
    task_monitoring_drag_leave(this, event);
});

taskMonitoringBindEvent('drop.taskMonitoringColumn', '[data-task-drop-zone]', function (event) {
    task_monitoring_drop(this, event);
});

//===================================================================================================
// SHARED FUNCTIONS
//===================================================================================================

function task_monitoring_show_toast(message) {
    var toast = document.getElementById('taskMonitoringToast');
    var toastMessage = document.getElementById('taskMonitoringToastMessage');

    toastMessage.textContent = message;
    toast.classList.add('is-visible');
    window.clearTimeout(taskMonitoringToastTimer);

    taskMonitoringToastTimer = window.setTimeout(function () {
        toast.classList.remove('is-visible');
    }, 2800);
}

function task_monitoring_close_modal() {
    var modal = document.getElementById('taskMonitoringModal');

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    if (taskMonitoringLastFocusedElement) {
        taskMonitoringLastFocusedElement.focus();
    }
}

function task_monitoring_close_details() {
    var modal = document.getElementById('taskMonitoringDetailsModal');

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    if (taskMonitoringLastFocusedElement) {
        taskMonitoringLastFocusedElement.focus();
    }
}

//===================================================================================================
// ALL FUNCTIONS RELATED TO TASK MONITORING
//===================================================================================================

function task_monitoring_summary() {
    var summaryContainer = $('#taskMonitoringSummaryContainer');
    var loadingMarkup = typeof window.loading === 'function'
        ? window.loading('Loading tasks...')
        : '<div class="task-monitoring-empty-state is-visible"><p>Loading tasks...</p></div>';

    summaryContainer.html(loadingMarkup);

    $.post('components/TaskCards_Summary.php', {}, function (html) {
        summaryContainer.html(html);
        task_monitoring_apply_summary_metrics();
        task_monitoring_refresh_counts();
    }).fail(function (xhr, status, error) {
        if (typeof window.handleAjaxError === 'function') {
            window.handleAjaxError(xhr, status, error);
            return;
        }

        summaryContainer.html(
            '<div class="task-monitoring-empty-state is-visible"><p>Unable to load the task board.</p></div>'
        );
    });
}

function task_monitoring_apply_summary_metrics() {
    var taskSummary = document.querySelector('[data-task-summary]');

    if (!taskSummary) {
        return;
    }

    document.getElementById('taskMonitoringActiveCount').textContent = taskSummary.getAttribute('data-active-count') || '0';
    document.getElementById('taskMonitoringDueTodayCount').textContent = taskSummary.getAttribute('data-due-today-count') || '0';
    document.getElementById('taskMonitoringCompletedCount').textContent = taskSummary.getAttribute('data-completed-count') || '0';
}

function task_monitoring_view(taskCard) {
    var modal = $('#taskMonitoringDetailsModal');
    var detailsContainer = $('#taskMonitoringDetailsContainer');

    if (!taskCard) {
        return;
    }

    taskMonitoringLastFocusedElement = document.activeElement;
    detailsContainer.html(
        '<section class="task-monitoring-modal task-monitoring-details-modal" role="dialog" aria-modal="true" aria-label="Loading task details">'
        + '<div class="task-monitoring-details-loading"><span></span><p>Loading task details...</p></div>'
        + '</section>'
    );
    modal.addClass('is-open').attr('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    $.post('components/TaskCards_Details.php', {
        TaskID: taskCard.getAttribute('data-task-id'),
        TaskTitle: taskCard.getAttribute('data-title'),
        TaskDescription: taskCard.getAttribute('data-description'),
        DisplayStatus: taskCard.getAttribute('data-status'),
        DueDate: taskCard.getAttribute('data-due-date'),
        Category: taskCard.getAttribute('data-category'),
        Priority: taskCard.getAttribute('data-priority'),
        Estimate: taskCard.getAttribute('data-estimate')
    }, function (html) {
        detailsContainer.html(html);

        var closeButton = detailsContainer.find('[data-task-details-close]').first();

        if (closeButton.length) {
            closeButton.trigger('focus');
        }
    }).fail(function (xhr, status, error) {
        if (typeof window.handleAjaxError === 'function') {
            window.handleAjaxError(xhr, status, error);
        }

        detailsContainer.html(
            '<section class="task-monitoring-modal task-monitoring-details-modal" role="dialog" aria-modal="true" aria-label="Unable to load task details">'
            + '<div class="task-monitoring-details-error"><h2>Unable to load task</h2><p>Please close this window and try again.</p>'
            + '<button class="task-monitoring-secondary-button" type="button" data-task-details-close>Close</button></div>'
            + '</section>'
        );
    });
}

function task_monitoring_open_add() {
    var form = document.getElementById('taskMonitoringForm');
    var modal = document.getElementById('taskMonitoringModal');
    var dueDateInput = document.getElementById('taskMonitoringDueDate');

    taskMonitoringLastFocusedElement = document.activeElement;
    form.reset();
    form.setAttribute('operation', '0');
    form.setAttribute('recID', '');
    document.getElementById('taskMonitoringModalTitle').textContent = 'Add a new task';
    document.getElementById('taskMonitoringSubmitText').textContent = 'Save Task';
    dueDateInput.value = dueDateInput.defaultValue;
    document.getElementById('taskMonitoringPriority').value = 'MEDIUM';

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    document.getElementById('taskMonitoringTitle').focus();
}

function task_monitoring_open_edit(taskCard) {
    var form = document.getElementById('taskMonitoringForm');
    var modal = document.getElementById('taskMonitoringModal');

    if (!taskCard) {
        return;
    }

    taskMonitoringLastFocusedElement = document.activeElement;
    form.setAttribute('operation', '1');
    form.setAttribute('recID', taskCard.getAttribute('data-task-id'));
    document.getElementById('taskMonitoringModalTitle').textContent = 'Edit task';
    document.getElementById('taskMonitoringSubmitText').textContent = 'Update Task';
    document.getElementById('taskMonitoringTitle').value = taskCard.getAttribute('data-title') || '';
    document.getElementById('taskMonitoringDescription').value = taskCard.getAttribute('data-description') || '';
    document.getElementById('taskMonitoringDueDate').value = taskCard.getAttribute('data-due-date') || '';
    document.getElementById('taskMonitoringStatus').value = taskCard.getAttribute('data-stored-status') || 'PENDING';
    document.getElementById('taskMonitoringCategory').value = taskCard.getAttribute('data-category') || '';
    document.getElementById('taskMonitoringPriority').value = (taskCard.getAttribute('data-priority') || 'MEDIUM').toUpperCase();

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    document.getElementById('taskMonitoringTitle').focus();
}

function task_monitoring_preview_save() {
    var form = document.getElementById('taskMonitoringForm');
    var isUpdate = form.getAttribute('operation') === '1';

    task_monitoring_close_modal();
    task_monitoring_show_toast(isUpdate
        ? 'The task update is ready for backend wiring.'
        : 'The new task form is ready for backend wiring.');
}

function task_monitoring_filter(searchValue) {
    var normalizedSearch = String(searchValue || '').trim().toLowerCase();
    var taskCards = document.querySelectorAll('[data-task-card]');

    taskCards.forEach(function (taskCard) {
        var searchableText = [
            taskCard.getAttribute('data-title'),
            taskCard.getAttribute('data-description'),
            taskCard.getAttribute('data-category'),
            taskCard.getAttribute('data-priority')
        ].join(' ').toLowerCase();

        taskCard.classList.toggle('task-monitoring-hidden', searchableText.indexOf(normalizedSearch) === -1);
    });

    task_monitoring_refresh_counts();
}

function task_monitoring_change_mobile_tab(status) {
    document.querySelectorAll('[data-mobile-status]').forEach(function (tabButton) {
        tabButton.classList.toggle('is-active', tabButton.getAttribute('data-mobile-status') === status);
    });

    document.querySelectorAll('[data-task-column]').forEach(function (taskColumn) {
        taskColumn.classList.toggle('is-mobile-active', taskColumn.getAttribute('data-task-column') === status);
    });
}

function task_monitoring_load_more(status) {
    var taskColumn = document.querySelector('[data-task-column="' + status + '"]');
    var hiddenCards;

    if (!taskColumn) {
        return;
    }

    hiddenCards = Array.prototype.slice.call(
        taskColumn.querySelectorAll('[data-task-card].task-monitoring-page-hidden'),
        0,
        3
    );

    hiddenCards.forEach(function (taskCard) {
        taskCard.classList.remove('task-monitoring-page-hidden');
    });

    task_monitoring_refresh_counts();
}

function task_monitoring_drag_start(taskCard, event) {
    var nativeEvent = event.originalEvent || event;

    taskMonitoringDraggedCard = taskCard;
    taskCard.classList.add('is-dragging');
    taskCard.setAttribute('aria-grabbed', 'true');

    if (nativeEvent.dataTransfer) {
        nativeEvent.dataTransfer.effectAllowed = 'move';
        nativeEvent.dataTransfer.setData('text/plain', taskCard.getAttribute('data-task-id'));
    }
}

function task_monitoring_drag_end() {
    if (taskMonitoringDraggedCard) {
        taskMonitoringDraggedCard.classList.remove('is-dragging');
        taskMonitoringDraggedCard.setAttribute('aria-grabbed', 'false');
    }

    document.querySelectorAll('[data-task-drop-zone]').forEach(function (dropZone) {
        dropZone.classList.remove('is-drop-target');
    });

    taskMonitoringDraggedCard = null;
}

function task_monitoring_drag_over(dropZone, event) {
    var targetStatus = dropZone.getAttribute('data-task-drop-zone');
    var sourceStatus = taskMonitoringDraggedCard
        ? taskMonitoringDraggedCard.getAttribute('data-status')
        : '';

    if (dropZone.getAttribute('data-drop-enabled') !== 'true') {
        return;
    }

    if (sourceStatus === 'late' && targetStatus !== 'complete') {
        return;
    }

    event.preventDefault();
    dropZone.classList.add('is-drop-target');

    if ((event.originalEvent || event).dataTransfer) {
        (event.originalEvent || event).dataTransfer.dropEffect = 'move';
    }
}

function task_monitoring_drag_leave(dropZone, event) {
    var relatedTarget = (event.originalEvent || event).relatedTarget;

    if (!relatedTarget || !dropZone.contains(relatedTarget)) {
        dropZone.classList.remove('is-drop-target');
    }
}

function task_monitoring_drop(dropZone, event) {
    var targetStatus = dropZone.getAttribute('data-task-drop-zone');

    event.preventDefault();
    dropZone.classList.remove('is-drop-target');

    if (!taskMonitoringDraggedCard || dropZone.getAttribute('data-drop-enabled') !== 'true') {
        return;
    }

    task_monitoring_move_card(taskMonitoringDraggedCard, targetStatus, dropZone);
    task_monitoring_drag_end();
}

function task_monitoring_move_card(taskCard, targetStatus, dropZone) {
    var sourceStatus = taskCard.getAttribute('data-status');
    var emptyState = dropZone.querySelector('[data-empty-state]');

    if (sourceStatus === targetStatus) {
        return;
    }

    if (sourceStatus === 'late' && targetStatus !== 'complete') {
        task_monitoring_show_toast('Late tasks stay in Late until their due date changes.');
        return;
    }

    dropZone.insertBefore(taskCard, emptyState);
    taskCard.setAttribute('data-status', targetStatus);
    taskCard.setAttribute('data-stored-status', targetStatus === 'complete' ? 'COMPLETED' : targetStatus.toUpperCase());
    task_monitoring_update_card_actions(taskCard, targetStatus);
    task_monitoring_refresh_counts();

    task_monitoring_show_toast('Task moved to ' + (targetStatus === 'complete' ? 'Complete' : targetStatus.charAt(0).toUpperCase() + targetStatus.slice(1)) + '.');
}

function task_monitoring_update_card_actions(taskCard, status) {
    var actionContainer = taskCard.querySelector('[data-task-card-actions]');
    var firstAction = document.createElement('button');
    var secondAction = document.createElement('button');

    if (!actionContainer) {
        return;
    }

    actionContainer.textContent = '';
    firstAction.className = 'task-monitoring-card-action';
    firstAction.type = 'button';

    if (status === 'complete') {
        firstAction.textContent = 'Reopen';
        firstAction.setAttribute('data-prototype-action', 'Reopen Task');
        actionContainer.appendChild(firstAction);
        return;
    }

    firstAction.textContent = status === 'pending' ? 'Start' : 'Pause';
    firstAction.setAttribute('data-prototype-action', status === 'pending' ? 'Move to Ongoing' : 'Move to Pending');
    actionContainer.appendChild(firstAction);

    secondAction.className = 'task-monitoring-card-action';
    secondAction.type = 'button';
    secondAction.textContent = 'Complete';
    secondAction.setAttribute('data-prototype-action', 'Complete Task');
    actionContainer.appendChild(secondAction);
}

function task_monitoring_refresh_counts() {
    var statusCounts = {
        pending: 0,
        ongoing: 0,
        complete: 0,
        late: 0
    };

    document.querySelectorAll('[data-task-column]').forEach(function (taskColumn) {
        var status = taskColumn.getAttribute('data-task-column');
        var visibleCards = taskColumn.querySelectorAll('[data-task-card]:not(.task-monitoring-hidden)').length;
        var loadedCards = taskColumn.querySelectorAll('[data-task-card]:not(.task-monitoring-hidden):not(.task-monitoring-page-hidden)').length;
        var totalCards = taskColumn.querySelectorAll('[data-task-card]').length;
        var emptyState = taskColumn.querySelector('[data-empty-state]');
        var countElement = document.querySelector('[data-column-count="' + status + '"]');
        var mobileCountElement = document.querySelector('[data-mobile-count="' + status + '"]');
        var visibleCountElement = taskColumn.querySelector('[data-visible-card-count="' + status + '"]');
        var totalCountElement = taskColumn.querySelector('[data-total-card-count="' + status + '"]');
        var loadMoreButton = taskColumn.querySelector('[data-task-load-more="' + status + '"]');

        statusCounts[status] = totalCards;
        emptyState.classList.toggle('is-visible', visibleCards === 0);
        countElement.textContent = visibleCards;
        mobileCountElement.textContent = visibleCards;

        if (visibleCountElement) {
            visibleCountElement.textContent = loadedCards;
        }

        if (totalCountElement) {
            totalCountElement.textContent = totalCards;
        }

        if (loadMoreButton) {
            loadMoreButton.hidden = taskColumn.querySelectorAll('[data-task-card].task-monitoring-page-hidden:not(.task-monitoring-hidden)').length === 0;
        }
    });

    document.getElementById('taskMonitoringActiveCount').textContent = statusCounts.pending + statusCounts.ongoing + statusCounts.late;
    document.getElementById('taskMonitoringCompletedCount').textContent = statusCounts.complete;
}
