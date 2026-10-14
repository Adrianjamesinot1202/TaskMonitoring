<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

$taskMonitoringToday = new DateTimeImmutable('today');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Monitoring</title>
    <link rel="stylesheet" href="style/task-monitoring-style.css?v=<?php echo filemtime(__DIR__ . '/style/task-monitoring-style.css'); ?>">
</head>
<body>
    <div class="task-monitoring-app">
        <aside class="task-monitoring-sidebar" aria-label="Primary navigation">
            <div>
                <div class="task-monitoring-brand">
                    <div class="task-monitoring-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </div>
                    <div class="task-monitoring-brand-text">
                        <p class="task-monitoring-brand-name">Task Monitor</p>
                        <p class="task-monitoring-brand-copy">Personal workspace</p>
                    </div>
                </div>

                <p class="task-monitoring-nav-label">Workspace</p>
                <nav class="task-monitoring-nav">
                    <a class="task-monitoring-nav-item is-active" href="#taskMonitoringBoard" aria-current="page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="2"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="2"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="2"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="2"></rect>
                        </svg>
                        <span>My Tasks</span>
                    </a>
                    <button class="task-monitoring-nav-item is-disabled" type="button" tabindex="-1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path d="M16 3v4M8 3v4M3 10h18"></path>
                        </svg>
                        <span>Calendar</span>
                        <small class="task-monitoring-coming-soon">Soon</small>
                    </button>
                    <button class="task-monitoring-nav-item is-disabled" type="button" tabindex="-1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19V9M10 19V5M16 19v-7M22 19V2"></path>
                        </svg>
                        <span>Reports</span>
                        <small class="task-monitoring-coming-soon">Soon</small>
                    </button>
                </nav>
            </div>

            <div class="task-monitoring-sidebar-footer">
                <div class="task-monitoring-profile">
                    <div class="task-monitoring-avatar">AJ</div>
                    <div class="task-monitoring-profile-copy-wrap">
                        <p class="task-monitoring-profile-name">My workspace</p>
                        <p class="task-monitoring-profile-copy">Local prototype</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="task-monitoring-main">
            <header class="task-monitoring-topbar">
                <div class="task-monitoring-heading">
                    <h1>Good day, AJ</h1>
                    <p><?php echo $taskMonitoringToday->format('l, F j, Y'); ?> · Here is what needs your attention.</p>
                </div>
                <div class="task-monitoring-topbar-actions">
                    <button class="task-monitoring-icon-button" type="button" aria-label="Notifications" data-prototype-action="Notifications">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                            <path d="M10 21h4"></path>
                        </svg>
                        <span class="task-monitoring-notification-dot" aria-hidden="true"></span>
                    </button>
                    <button class="task-monitoring-primary-button" id="btnTaskMonitoringAdd" type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                        <span>Add Task</span>
                    </button>
                </div>
            </header>

            <section class="task-monitoring-overview" aria-label="Task overview">
                <article class="task-monitoring-focus-card">
                    <div>
                        <small>Today’s focus</small>
                        <h2>Keep the board simple: finish what is active before adding more work.</h2>
                    </div>
                    <div class="task-monitoring-progress-ring" aria-label="Progress 68 percent">
                        <span>68%</span>
                    </div>
                </article>

                <article class="task-monitoring-metric-card">
                    <div class="task-monitoring-metric-icon is-active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="task-monitoring-metric-value" id="taskMonitoringActiveCount">0</p>
                        <p class="task-monitoring-metric-label">Active tasks</p>
                    </div>
                </article>

                <article class="task-monitoring-metric-card">
                    <div class="task-monitoring-metric-icon is-today">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="task-monitoring-metric-value" id="taskMonitoringDueTodayCount">0</p>
                        <p class="task-monitoring-metric-label">Due today</p>
                    </div>
                </article>

                <article class="task-monitoring-metric-card">
                    <div class="task-monitoring-metric-icon is-complete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="task-monitoring-metric-value" id="taskMonitoringCompletedCount">0</p>
                        <p class="task-monitoring-metric-label">Completed</p>
                    </div>
                </article>
            </section>

            <section id="taskMonitoringBoard" aria-labelledby="taskMonitoringBoardTitle">
                <div class="task-monitoring-toolbar">
                    <div class="task-monitoring-section-title">
                        <h2 id="taskMonitoringBoardTitle">My task board</h2>
                        <p>Drag tasks between Pending, Ongoing, and Complete. Late is detected automatically.</p>
                    </div>
                    <label class="task-monitoring-search" for="taskMonitoringSearch">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="M20 20l-4-4"></path>
                        </svg>
                        <input id="taskMonitoringSearch" type="search" placeholder="Search tasks" autocomplete="off">
                    </label>
                </div>

                <div id="taskMonitoringSummaryContainer" aria-live="polite">
                    <div class="task-monitoring-empty-state is-visible">
                        <p>Loading tasks...</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div class="task-monitoring-modal-backdrop" id="taskMonitoringModal" aria-hidden="true">
        <section class="task-monitoring-modal" role="dialog" aria-modal="true" aria-labelledby="taskMonitoringModalTitle">
            <header class="task-monitoring-modal-header">
                <div>
                    <h2 id="taskMonitoringModalTitle">Add a new task</h2>
                    <p>Keep it clear and give it a realistic due date.</p>
                </div>
                <button class="task-monitoring-modal-close" type="button" aria-label="Close modal" data-task-modal-close>×</button>
            </header>

            <form id="taskMonitoringForm" operation="0" recID="">
                <div class="task-monitoring-form-body">
                    <div class="task-monitoring-field">
                        <label for="taskMonitoringTitle">Task title <span>*</span></label>
                        <input id="taskMonitoringTitle" name="TaskTitle" type="text" maxlength="150" placeholder="What needs to be done?" required>
                    </div>

                    <div class="task-monitoring-field">
                        <label for="taskMonitoringDescription">Description</label>
                        <textarea id="taskMonitoringDescription" name="TaskDescription" maxlength="1000" placeholder="Add useful context or a short checklist..."></textarea>
                        <p class="task-monitoring-field-hint">Optional. Keep the details short enough to scan from the board.</p>
                    </div>

                    <div class="task-monitoring-form-row">
                        <div class="task-monitoring-field">
                            <label for="taskMonitoringDueDate">Due date <span>*</span></label>
                            <input id="taskMonitoringDueDate" name="DueDate" type="date" value="<?php echo $taskMonitoringToday->format('Y-m-d'); ?>" required>
                        </div>
                        <div class="task-monitoring-field">
                            <label for="taskMonitoringStatus">Status <span>*</span></label>
                            <select id="taskMonitoringStatus" name="TaskStatus" required>
                                <option value="PENDING">Pending</option>
                                <option value="ONGOING">Ongoing</option>
                            </select>
                        </div>
                    </div>

                    <div class="task-monitoring-form-row">
                        <div class="task-monitoring-field">
                            <label for="taskMonitoringCategory">Category</label>
                            <input id="taskMonitoringCategory" name="Category" type="text" maxlength="60" placeholder="Example: Office">
                        </div>
                        <div class="task-monitoring-field">
                            <label for="taskMonitoringPriority">Priority</label>
                            <select id="taskMonitoringPriority" name="Priority">
                                <option value="LOW">Low</option>
                                <option value="MEDIUM" selected>Medium</option>
                                <option value="HIGH">High</option>
                            </select>
                        </div>
                    </div>
                </div>

                <footer class="task-monitoring-modal-footer">
                    <button class="task-monitoring-secondary-button" type="button" data-task-modal-close>Cancel</button>
                    <button class="task-monitoring-primary-button" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5"></path>
                        </svg>
                        <span id="taskMonitoringSubmitText">Save Task</span>
                    </button>
                </footer>
            </form>
        </section>
    </div>

    <div class="task-monitoring-toast" id="taskMonitoringToast" role="status" aria-live="polite">
        <span class="task-monitoring-toast-mark">✓</span>
        <p id="taskMonitoringToastMessage">Prototype action completed.</p>
    </div>

    <script
        defer
        src="https://code.jquery.com/jquery-3.7.1.min.js"
    ></script>
    <script
        defer
        src="script/task-monitoring-script.js?v=<?php echo (new DateTime())->format('YmdHis'); ?>"
    ></script>
</body>
</html>
