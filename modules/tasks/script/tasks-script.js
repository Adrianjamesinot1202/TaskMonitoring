//===================================================================================================
// MODULE VARIABLES
//===================================================================================================

var taskMonitoringLastFocusedElement = null;
var taskMonitoringDraggedCard = null;
var taskMonitoringLayout = 'board';
var taskMonitoringListStatus = 'all';
var taskMonitoringListPage = 1;
var taskMonitoringListPageSize = 8;
var taskMonitoringTabResizeObserver = null;

//===================================================================================================
// BIND EVENTS
//===================================================================================================

bindEvent('click.taskMonitoringAdd', '#btnTaskMonitoringAdd', function () {
    tasks_open_add();
});

bindEvent('click.taskMonitoringView', '[data-task-view]', function () {
    tasks_view(this.closest('[data-task-card]'));
});

bindEvent('click.taskMonitoringEdit', '[data-task-edit]', function () {
    tasks_open_edit(this.closest('[data-task-card]'));
});

bindEvent('change.taskMonitoringComplete', '[data-task-complete]', function () {
    tasks_complete_task(this);
});

bindEvent('click.taskMonitoringClose', '[data-task-modal-close]', function () {
    tasks_close_modal();
});

bindEvent('click.taskMonitoringBackdrop', '#taskMonitoringModal', function (event) {
    if (event.target.id === 'taskMonitoringModal') {
        tasks_close_modal();
    }
});

bindEvent('click.taskMonitoringDetailsClose', '[data-task-details-close]', function () {
    tasks_close_details();
});

bindEvent('click.taskMonitoringDetailsBackdrop', '#taskMonitoringDetailsModal', function (event) {
    if (event.target.id === 'taskMonitoringDetailsModal') {
        tasks_close_details();
    }
});

bindEvent('click.taskMonitoringDetailsEdit', '[data-task-details-edit]', function () {
    var taskID = this.getAttribute('data-task-details-edit');
    var taskCard = document.querySelector('[data-task-card][data-task-id="' + taskID + '"]');

    tasks_close_details();
    tasks_open_edit(taskCard);
});

bindEvent('submit.taskMonitoringForm', '#taskMonitoringForm', function () {
    tasks_preview_save();
});

bindEvent('input.taskMonitoringSearch', '#taskMonitoringSearch', function () {
    tasks_filter(this.value);
});

bindEvent('click.taskMonitoringMobileTab', '[data-mobile-status]', function () {
    tasks_change_mobile_tab(this.getAttribute('data-mobile-status'));
});

bindEvent('click.taskMonitoringPrototype', '[data-prototype-action]', function () {
    showToast(this.getAttribute('data-prototype-action') + ' will be connected during the backend phase.', 'info');
});

bindEvent('click.taskMonitoringLoadMore', '[data-task-load-more]', function () {
    tasks_load_more(this.getAttribute('data-task-load-more'));
});

bindEvent('click.taskMonitoringListStatus', '[data-list-status]', function () {
    tasks_change_list_status(this.getAttribute('data-list-status'));
});

bindEvent('click.taskMonitoringListPage', '[data-list-page]', function () {
    taskMonitoringListPage += Number(this.getAttribute('data-list-page'));
    tasks_refresh_list();
});

bindEvent('dragstart.taskMonitoringCard', '[data-task-card]', function (event) {
    tasks_drag_start(this, event);
});

bindEvent('dragend.taskMonitoringCard', '[data-task-card]', function () {
    tasks_drag_end();
});

bindEvent('dragover.taskMonitoringColumn', '[data-task-drop-zone]', function (event) {
    tasks_drag_over(this, event);
});

bindEvent('dragleave.taskMonitoringColumn', '[data-task-drop-zone]', function (event) {
    tasks_drag_leave(this, event);
});

bindEvent('drop.taskMonitoringColumn', '[data-task-drop-zone]', function (event) {
    tasks_drop(this, event);
});

//===================================================================================================
// SHARED FUNCTIONS
//===================================================================================================

function tasks_close_modal() {
    var modal = document.getElementById('taskMonitoringModal');

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    if (taskMonitoringLastFocusedElement) {
        taskMonitoringLastFocusedElement.focus();
    }
}

function tasks_close_details() {
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

function tasks_init() {
    taskMonitoringLayout = document.body.getAttribute('data-task-page') === 'dashboard' ? 'list' : 'board';
    taskMonitoringListStatus = 'all';
    taskMonitoringListPage = 1;
    taskMonitoringDraggedCard = null;

    if (taskMonitoringTabResizeObserver) {
        taskMonitoringTabResizeObserver.disconnect();
    }

    return tasks_summary();
}

function tasks_summary() {
    var summaryContainer = $('#taskMonitoringSummaryContainer');
    var loadingMarkup = typeof window.loading === 'function'
        ? window.loading('Loading tasks...')
        : '<div class="task-monitoring-empty-state is-visible"><p>Loading tasks...</p></div>';

    summaryContainer.html(loadingMarkup);

    return $.post('modules/tasks/components/TaskCards_Summary.php', {}, function (html) {
        if (taskMonitoringTabResizeObserver) {
            taskMonitoringTabResizeObserver.disconnect();
        }

        summaryContainer.html(html);
        tasks_change_layout(taskMonitoringLayout);
        tasks_filter(document.getElementById('taskMonitoringSearch').value);

        if (typeof window.ResizeObserver === 'function') {
            taskMonitoringTabResizeObserver = new ResizeObserver(tasks_position_list_indicator);
            taskMonitoringTabResizeObserver.observe(summaryContainer.find('.task-monitoring-list-tabs')[0]);
        }
    }).fail(function (xhr, status, error) {
        if (status === 'abort') {
            return;
        }

        summaryContainer.html(
            '<div class="task-monitoring-empty-state is-visible"><p>Unable to load the task board.</p></div>'
        );

        if (typeof window.handleAjaxError === 'function') {
            window.handleAjaxError(xhr, status, error);
        }
    }).always(function () {
        app_module_component_ready('taskMonitoringSummaryContainer');
    });
}

function tasks_view(taskCard) {
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

    $.post('modules/tasks/components/TaskCards_Details.php', {
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

function tasks_open_add() {
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

function tasks_open_edit(taskCard) {
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

function tasks_preview_save() {
    var form = document.getElementById('taskMonitoringForm');
    var isUpdate = form.getAttribute('operation') === '1';

    tasks_close_modal();
    showToast(isUpdate
        ? 'The task update is ready for backend wiring.'
        : 'The new task form is ready for backend wiring.', 'info');
}

function tasks_filter(searchValue) {
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

    taskMonitoringListPage = 1;
    tasks_refresh_counts();
}

function tasks_change_layout(layout) {
    var summary = document.querySelector('[data-task-summary]');

    taskMonitoringLayout = layout === 'list' ? 'list' : 'board';

    if (!summary) {
        return;
    }

    summary.classList.toggle('is-list', taskMonitoringLayout === 'list');
    summary.querySelectorAll('[data-task-card]').forEach(function (card) {
        card.setAttribute('draggable', String(taskMonitoringLayout === 'board'));
    });
    tasks_refresh_list();
}

function tasks_refresh_list() {
    var summary = document.querySelector('[data-task-summary]');
    var matchingCards = [];
    var statusCardCount = 0;

    if (!summary) {
        return;
    }

    summary.classList.toggle('is-status-filtered', taskMonitoringListStatus !== 'all');

    summary.querySelectorAll('[data-task-card]').forEach(function (card) {
        var matchesStatus = taskMonitoringListStatus === 'all'
            || card.getAttribute('data-status') === taskMonitoringListStatus;

        card.classList.add('task-monitoring-list-hidden');

        if (matchesStatus) {
            statusCardCount++;
        }

        if (matchesStatus && !card.classList.contains('task-monitoring-hidden')) {
            matchingCards.push(card);
        }
    });

    var pageCount = Math.max(1, Math.ceil(matchingCards.length / taskMonitoringListPageSize));
    taskMonitoringListPage = Math.max(1, Math.min(taskMonitoringListPage, pageCount));
    var pageStart = (taskMonitoringListPage - 1) * taskMonitoringListPageSize;
    var pageEnd = Math.min(pageStart + taskMonitoringListPageSize, matchingCards.length);

    matchingCards.slice(pageStart, pageEnd).forEach(function (card) {
        card.classList.remove('task-monitoring-list-hidden');
    });

    summary.querySelectorAll('[data-list-status]').forEach(function (button) {
        var isActive = button.getAttribute('data-list-status') === taskMonitoringListStatus;

        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-pressed', String(isActive));
    });

    summary.querySelector('[data-list-results]').textContent = matchingCards.length === 0
        ? '0 tasks'
        : 'Showing ' + (pageStart + 1) + '–' + pageEnd + ' of ' + matchingCards.length + ' tasks';
    summary.querySelector('[data-list-page-label]').textContent = taskMonitoringListPage + ' / ' + pageCount;
    summary.querySelector('[data-list-page="-1"]').disabled = taskMonitoringListPage === 1;
    summary.querySelector('[data-list-page="1"]').disabled = taskMonitoringListPage === pageCount;
    var emptyState = summary.querySelector('[data-list-empty]');
    var emptyMessage = emptyState.getAttribute('data-empty-message');

    if (taskMonitoringListStatus === 'complete') {
        emptyMessage = '';
    } else if (statusCardCount > 0) {
        emptyMessage = 'No matching tasks. Try another search.';
    } else if (taskMonitoringListStatus !== 'all') {
        var statusColumn = summary.querySelector('[data-task-column="' + taskMonitoringListStatus + '"]');
        emptyMessage = statusColumn.getAttribute('data-empty-message');
    }

    summary.querySelector('[data-list-empty-message]').textContent = emptyMessage;
    emptyState.hidden = matchingCards.length !== 0 || emptyMessage === '';
    summary.classList.toggle('is-list-empty', matchingCards.length === 0);
    tasks_position_list_indicator();
}

function tasks_change_list_status(status) {
    var summary = document.querySelector('[data-task-summary]');

    if (!summary || status === taskMonitoringListStatus) {
        return;
    }

    taskMonitoringListStatus = status;
    taskMonitoringListPage = 1;
    tasks_refresh_list();

    summary.classList.remove('is-list-transition');
    void summary.offsetWidth;
    summary.classList.add('is-list-transition');
}

function tasks_position_list_indicator() {
    var summary = document.querySelector('[data-task-summary].is-list');

    if (!summary) {
        return;
    }

    var activeTab = summary.querySelector('[data-list-status].is-active');
    var indicator = summary.querySelector('[data-list-tab-indicator]');

    if (!activeTab || !indicator) {
        return;
    }

    indicator.style.width = activeTab.offsetWidth + 'px';
    indicator.style.height = activeTab.offsetHeight + 'px';
    indicator.style.transform = 'translate(' + activeTab.offsetLeft + 'px, ' + activeTab.offsetTop + 'px)';
    indicator.classList.add('is-positioned');
}

function tasks_change_mobile_tab(status) {
    document.querySelectorAll('[data-mobile-status]').forEach(function (tabButton) {
        tabButton.classList.toggle('is-active', tabButton.getAttribute('data-mobile-status') === status);
    });

    document.querySelectorAll('[data-task-column]').forEach(function (taskColumn) {
        taskColumn.classList.toggle('is-mobile-active', taskColumn.getAttribute('data-task-column') === status);
    });
}

function tasks_load_more(status) {
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

    tasks_refresh_counts();
}

function tasks_drag_start(taskCard, event) {
    var nativeEvent = event.originalEvent || event;

    taskMonitoringDraggedCard = taskCard;
    taskCard.classList.add('is-dragging');
    taskCard.setAttribute('aria-grabbed', 'true');

    if (nativeEvent.dataTransfer) {
        nativeEvent.dataTransfer.effectAllowed = 'move';
        nativeEvent.dataTransfer.setData('text/plain', taskCard.getAttribute('data-task-id'));
    }
}

function tasks_drag_end() {
    if (taskMonitoringDraggedCard) {
        taskMonitoringDraggedCard.classList.remove('is-dragging');
        taskMonitoringDraggedCard.setAttribute('aria-grabbed', 'false');
    }

    document.querySelectorAll('[data-task-drop-zone]').forEach(function (dropZone) {
        dropZone.classList.remove('is-drop-target');
    });

    taskMonitoringDraggedCard = null;
}

function tasks_drag_over(dropZone, event) {
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

function tasks_drag_leave(dropZone, event) {
    var relatedTarget = (event.originalEvent || event).relatedTarget;

    if (!relatedTarget || !dropZone.contains(relatedTarget)) {
        dropZone.classList.remove('is-drop-target');
    }
}

function tasks_drop(dropZone, event) {
    var targetStatus = dropZone.getAttribute('data-task-drop-zone');

    event.preventDefault();
    dropZone.classList.remove('is-drop-target');

    if (!taskMonitoringDraggedCard || dropZone.getAttribute('data-drop-enabled') !== 'true') {
        return;
    }

    tasks_move_card(taskMonitoringDraggedCard, targetStatus, dropZone);
    tasks_drag_end();
}

function tasks_complete_task(checkbox) {
    var taskCard = checkbox.closest('[data-task-card]');
    var completeColumn = document.querySelector('[data-task-drop-zone="complete"]');

    if (!checkbox.checked || !taskCard || !completeColumn || taskCard.classList.contains('is-completing')) {
        return;
    }

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    checkbox.disabled = true;
    taskCard.classList.add('is-completing');

    // Let the checkmark and row feedback finish before changing the filtered list.
    window.setTimeout(function () {
        if (!taskCard.isConnected) {
            return;
        }

        taskCard.classList.remove('is-completing');
        tasks_move_card(taskCard, 'complete', completeColumn);
    }, reduceMotion ? 0 : 240);
}

function tasks_move_card(taskCard, targetStatus, dropZone) {
    var sourceStatus = taskCard.getAttribute('data-status');
    var emptyState = dropZone.querySelector('[data-empty-state]');

    if (sourceStatus === targetStatus) {
        return;
    }

    if (sourceStatus === 'late' && targetStatus !== 'complete') {
        showToast('Late tasks stay in Late until their due date changes.', 'warning');
        return;
    }

    dropZone.insertBefore(taskCard, emptyState);
    taskCard.setAttribute('data-status', targetStatus);
    taskCard.setAttribute('data-stored-status', targetStatus === 'complete' ? 'COMPLETED' : targetStatus.toUpperCase());
    tasks_update_card_actions(taskCard, targetStatus);
    tasks_refresh_counts();

    showToast('Task moved to ' + (targetStatus === 'complete' ? 'Complete' : targetStatus.charAt(0).toUpperCase() + targetStatus.slice(1)) + '.', 'success');
}

function tasks_update_card_actions(taskCard, status) {
    var actionContainer = taskCard.querySelector('[data-task-card-actions]');
    var firstAction = document.createElement('button');
    var secondAction = document.createElement('button');
    var listStatus = taskCard.querySelector('[data-list-card-status]');
    var completeCheckbox = taskCard.querySelector('[data-task-complete]');

    if (completeCheckbox) {
        completeCheckbox.checked = status === 'complete';
        completeCheckbox.disabled = status === 'complete';
        completeCheckbox.setAttribute('aria-label', (status === 'complete' ? 'Completed: ' : 'Mark complete: ') + taskCard.getAttribute('data-title'));
    }

    if (listStatus) {
        listStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    }

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

function tasks_refresh_counts() {
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

        taskColumn.classList.toggle('is-column-empty', totalCards === 0);
        emptyState.classList.toggle('is-visible', visibleCards === 0 && taskColumn.getAttribute('data-empty-message') !== '');
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
    tasks_refresh_list();

    if (typeof window.dashboard_refresh_metrics === 'function') {
        dashboard_refresh_metrics();
    }
}
