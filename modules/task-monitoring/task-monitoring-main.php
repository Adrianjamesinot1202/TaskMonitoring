<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

$taskMonitoringToday = new DateTimeImmutable('today');
$taskMonitoringTasks = [
    [
        'TaskID' => 101,
        'TaskTitle' => 'Build the task entry modal',
        'TaskDescription' => 'Finalize the fields, spacing, validation states, and action buttons for creating a task.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+1 day')->format('Y-m-d'),
        'Category' => 'UI Design',
        'Priority' => 'High',
        'Estimate' => '2h',
    ],
    [
        'TaskID' => 102,
        'TaskTitle' => 'Prepare Supabase table plan',
        'TaskDescription' => 'List the columns and indexes we will need after the interface is approved.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('+3 days')->format('Y-m-d'),
        'Category' => 'Backend',
        'Priority' => 'Medium',
        'Estimate' => '1h',
    ],
    [
        'TaskID' => 103,
        'TaskTitle' => 'Review the responsive board',
        'TaskDescription' => 'Check the column layout on desktop and the tab experience on smaller screens.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->format('Y-m-d'),
        'Category' => 'Review',
        'Priority' => 'High',
        'Estimate' => '45m',
    ],
    [
        'TaskID' => 104,
        'TaskTitle' => 'Define useful empty states',
        'TaskDescription' => 'Make each status column remain helpful even when it does not contain a task.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+2 days')->format('Y-m-d'),
        'Category' => 'Content',
        'Priority' => 'Low',
        'Estimate' => '30m',
    ],
    [
        'TaskID' => 105,
        'TaskTitle' => 'Confirm the task lifecycle',
        'TaskDescription' => 'Decide how completed tasks leave the active board while keeping their history.',
        'TaskStatus' => 'PENDING',
        'DueDate' => $taskMonitoringToday->modify('-2 days')->format('Y-m-d'),
        'Category' => 'Planning',
        'Priority' => 'High',
        'Estimate' => '20m',
    ],
    [
        'TaskID' => 106,
        'TaskTitle' => 'Review validation messages',
        'TaskDescription' => 'Make the form feedback clear, concise, and positioned beside the affected fields.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('-1 day')->format('Y-m-d'),
        'Category' => 'Quality',
        'Priority' => 'Medium',
        'Estimate' => '40m',
    ],
    [
        'TaskID' => 107,
        'TaskTitle' => 'Polish card interactions',
        'TaskDescription' => 'Review hover states, focus states, action placement, and touch target sizes.',
        'TaskStatus' => 'ONGOING',
        'DueDate' => $taskMonitoringToday->modify('+5 days')->format('Y-m-d'),
        'Category' => 'UI Design',
        'Priority' => 'Medium',
        'Estimate' => '1.5h',
    ],
    [
        'TaskID' => 108,
        'TaskTitle' => 'Approve the visual direction',
        'TaskDescription' => 'Choose the final colors, density, and page structure before backend development begins.',
        'TaskStatus' => 'COMPLETED',
        'DueDate' => $taskMonitoringToday->modify('-1 day')->format('Y-m-d'),
        'Category' => 'Review',
        'Priority' => 'Medium',
        'Estimate' => '30m',
    ],
];

$taskMonitoringColumns = [
    'pending' => [
        'label' => 'Pending',
        'description' => 'Ready to be picked up',
    ],
    'ongoing' => [
        'label' => 'Ongoing',
        'description' => 'Currently in progress',
    ],
    'late' => [
        'label' => 'Late',
        'description' => 'Needs immediate attention',
    ],
];

$taskMonitoringGroupedTasks = [
    'pending' => [],
    'ongoing' => [],
    'late' => [],
];

$taskMonitoringCompletedCount = 0;
$taskMonitoringDueTodayCount = 0;

foreach ($taskMonitoringTasks as $taskMonitoringTask) {
    if ($taskMonitoringTask['TaskStatus'] === 'COMPLETED') {
        $taskMonitoringCompletedCount++;
        continue;
    }

    $taskMonitoringDueDate = new DateTimeImmutable($taskMonitoringTask['DueDate']);

    if ($taskMonitoringDueDate == $taskMonitoringToday) {
        $taskMonitoringDueTodayCount++;
    }

    if ($taskMonitoringDueDate < $taskMonitoringToday) {
        $taskMonitoringDisplayStatus = 'late';
    } elseif ($taskMonitoringTask['TaskStatus'] === 'ONGOING') {
        $taskMonitoringDisplayStatus = 'ongoing';
    } else {
        $taskMonitoringDisplayStatus = 'pending';
    }

    $taskMonitoringTask['DisplayStatus'] = $taskMonitoringDisplayStatus;
    $taskMonitoringGroupedTasks[$taskMonitoringDisplayStatus][] = $taskMonitoringTask;
}

$taskMonitoringActiveCount = count($taskMonitoringGroupedTasks['pending'])
    + count($taskMonitoringGroupedTasks['ongoing'])
    + count($taskMonitoringGroupedTasks['late']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Monitoring</title>
    <style>
        :root {
            --task-bg: #f4f6fb;
            --task-surface: #ffffff;
            --task-surface-soft: #f8f9fc;
            --task-border: #e7e9f1;
            --task-text: #172033;
            --task-muted: #6d7485;
            --task-primary: #5b5ce2;
            --task-primary-dark: #4748c6;
            --task-primary-soft: #eeefff;
            --task-pending: #c78018;
            --task-pending-soft: #fff7e8;
            --task-ongoing: #2776d2;
            --task-ongoing-soft: #edf6ff;
            --task-late: #d24e62;
            --task-late-soft: #fff0f3;
            --task-success: #218867;
            --task-success-soft: #eaf8f3;
            --task-sidebar: #171a2c;
            --task-sidebar-muted: #979caf;
            --task-shadow: 0 16px 38px rgba(31, 37, 68, 0.08);
            --task-radius-large: 24px;
            --task-radius-medium: 16px;
            --task-radius-small: 11px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-width: 320px;
            background: var(--task-bg);
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--task-text);
            background: var(--task-bg);
            font-family: Inter, ui-sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid rgba(91, 92, 226, 0.2);
            outline-offset: 2px;
        }

        .task-monitoring-app {
            display: grid;
            grid-template-columns: 232px minmax(0, 1fr);
            min-height: 100vh;
        }

        .task-monitoring-sidebar {
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 26px 18px 20px;
            color: #ffffff;
            background: var(--task-sidebar);
        }

        .task-monitoring-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 28px;
        }

        .task-monitoring-brand-mark {
            display: grid;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            place-items: center;
            background: linear-gradient(145deg, #7778ff, #5051d8);
            box-shadow: 0 9px 24px rgba(91, 92, 226, 0.38);
        }

        .task-monitoring-brand-mark svg {
            width: 21px;
            height: 21px;
        }

        .task-monitoring-brand-name {
            margin: 0;
            font-size: 16px;
            font-weight: 750;
            letter-spacing: -0.02em;
        }

        .task-monitoring-brand-copy {
            margin: 1px 0 0;
            color: var(--task-sidebar-muted);
            font-size: 11px;
        }

        .task-monitoring-nav-label {
            margin: 10px 12px 9px;
            color: #666c83;
            font-size: 10px;
            font-weight: 750;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .task-monitoring-nav {
            display: grid;
            gap: 5px;
        }

        .task-monitoring-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 44px;
            padding: 0 13px;
            border: 0;
            border-radius: 11px;
            color: var(--task-sidebar-muted);
            background: transparent;
            text-align: left;
            text-decoration: none;
        }

        .task-monitoring-nav-item svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
        }

        .task-monitoring-nav-item.is-active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.09);
            box-shadow: inset 3px 0 var(--task-primary);
        }

        .task-monitoring-nav-item.is-disabled {
            cursor: default;
            opacity: 0.48;
        }

        .task-monitoring-coming-soon {
            margin-left: auto;
            padding: 3px 7px;
            border-radius: 999px;
            color: #bfc2ff;
            background: rgba(91, 92, 226, 0.18);
            font-size: 9px;
            font-weight: 750;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .task-monitoring-sidebar-footer {
            margin-top: auto;
            padding: 14px 10px 2px;
        }

        .task-monitoring-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .task-monitoring-avatar {
            display: grid;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            place-items: center;
            color: #ffffff;
            background: #3a3e58;
            font-size: 12px;
            font-weight: 750;
        }

        .task-monitoring-profile-name {
            margin: 0;
            color: #ffffff;
            font-size: 13px;
            font-weight: 650;
        }

        .task-monitoring-profile-copy {
            margin: 1px 0 0;
            color: var(--task-sidebar-muted);
            font-size: 11px;
        }

        .task-monitoring-main {
            min-width: 0;
            padding: 30px 32px 40px;
        }

        .task-monitoring-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 26px;
        }

        .task-monitoring-heading h1 {
            margin: 0;
            font-size: clamp(25px, 3vw, 34px);
            line-height: 1.15;
            letter-spacing: -0.045em;
        }

        .task-monitoring-heading p {
            margin: 7px 0 0;
            color: var(--task-muted);
            font-size: 14px;
        }

        .task-monitoring-topbar-actions {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .task-monitoring-icon-button,
        .task-monitoring-primary-button,
        .task-monitoring-secondary-button,
        .task-monitoring-card-action,
        .task-monitoring-card-menu,
        .task-monitoring-tab {
            border: 0;
        }

        .task-monitoring-icon-button {
            position: relative;
            display: grid;
            width: 42px;
            height: 42px;
            border: 1px solid var(--task-border);
            border-radius: 12px;
            place-items: center;
            color: #4c5467;
            background: var(--task-surface);
        }

        .task-monitoring-icon-button svg {
            width: 18px;
            height: 18px;
        }

        .task-monitoring-notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            background: var(--task-late);
        }

        .task-monitoring-primary-button,
        .task-monitoring-secondary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 17px;
            border-radius: 12px;
            font-weight: 700;
        }

        .task-monitoring-primary-button {
            color: #ffffff;
            background: var(--task-primary);
            box-shadow: 0 10px 22px rgba(91, 92, 226, 0.22);
        }

        .task-monitoring-primary-button:hover {
            background: var(--task-primary-dark);
        }

        .task-monitoring-primary-button svg,
        .task-monitoring-secondary-button svg {
            width: 17px;
            height: 17px;
        }

        .task-monitoring-secondary-button {
            border: 1px solid var(--task-border);
            color: var(--task-text);
            background: #ffffff;
            box-shadow: none;
        }

        .task-monitoring-overview {
            display: grid;
            grid-template-columns: minmax(260px, 1.7fr) repeat(3, minmax(150px, 0.7fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .task-monitoring-focus-card,
        .task-monitoring-metric-card {
            min-height: 112px;
            border: 1px solid var(--task-border);
            border-radius: var(--task-radius-medium);
            background: var(--task-surface);
            box-shadow: 0 8px 28px rgba(31, 37, 68, 0.045);
        }

        .task-monitoring-focus-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            overflow: hidden;
            padding: 20px 23px;
            border-color: transparent;
            color: #ffffff;
            background: linear-gradient(120deg, #5758d9 0%, #7375ed 64%, #8a8cf6 100%);
        }

        .task-monitoring-focus-card::after {
            position: absolute;
            right: -34px;
            bottom: -65px;
            width: 170px;
            height: 170px;
            border: 27px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            content: "";
        }

        .task-monitoring-focus-card small {
            display: block;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 11px;
            font-weight: 750;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .task-monitoring-focus-card h2 {
            max-width: 430px;
            margin: 0;
            font-size: 18px;
            line-height: 1.35;
            letter-spacing: -0.02em;
        }

        .task-monitoring-progress-ring {
            position: relative;
            z-index: 1;
            display: grid;
            width: 66px;
            height: 66px;
            flex: 0 0 auto;
            border: 7px solid rgba(255, 255, 255, 0.2);
            border-top-color: #ffffff;
            border-right-color: #ffffff;
            border-radius: 50%;
            place-items: center;
            font-size: 14px;
            font-weight: 800;
            transform: rotate(14deg);
        }

        .task-monitoring-progress-ring span {
            transform: rotate(-14deg);
        }

        .task-monitoring-metric-card {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 18px;
        }

        .task-monitoring-metric-icon {
            display: grid;
            width: 42px;
            height: 42px;
            flex: 0 0 auto;
            border-radius: 13px;
            place-items: center;
        }

        .task-monitoring-metric-icon svg {
            width: 19px;
            height: 19px;
        }

        .task-monitoring-metric-icon.is-active {
            color: var(--task-primary);
            background: var(--task-primary-soft);
        }

        .task-monitoring-metric-icon.is-today {
            color: var(--task-pending);
            background: var(--task-pending-soft);
        }

        .task-monitoring-metric-icon.is-complete {
            color: var(--task-success);
            background: var(--task-success-soft);
        }

        .task-monitoring-metric-value {
            margin: 0;
            font-size: 23px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .task-monitoring-metric-label {
            margin: 6px 0 0;
            color: var(--task-muted);
            font-size: 11px;
        }

        .task-monitoring-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .task-monitoring-section-title h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: -0.025em;
        }

        .task-monitoring-section-title p {
            margin: 3px 0 0;
            color: var(--task-muted);
            font-size: 12px;
        }

        .task-monitoring-search {
            position: relative;
            width: min(100%, 285px);
        }

        .task-monitoring-search svg {
            position: absolute;
            top: 50%;
            left: 14px;
            width: 17px;
            height: 17px;
            color: #9096a7;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .task-monitoring-search input {
            width: 100%;
            height: 42px;
            padding: 0 14px 0 40px;
            border: 1px solid var(--task-border);
            border-radius: 12px;
            color: var(--task-text);
            background: var(--task-surface);
        }

        .task-monitoring-search input::placeholder {
            color: #9ba0ae;
        }

        .task-monitoring-mobile-tabs {
            display: none;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin-bottom: 14px;
            padding: 5px;
            border: 1px solid var(--task-border);
            border-radius: 13px;
            background: #ffffff;
        }

        .task-monitoring-tab {
            min-height: 38px;
            padding: 0 10px;
            border-radius: 9px;
            color: var(--task-muted);
            background: transparent;
            font-size: 12px;
            font-weight: 700;
        }

        .task-monitoring-tab.is-active {
            color: #ffffff;
            background: var(--task-primary);
        }

        .task-monitoring-board {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            align-items: start;
        }

        .task-monitoring-column {
            min-width: 0;
            padding: 15px;
            border: 1px solid var(--task-border);
            border-radius: var(--task-radius-large);
            background: rgba(255, 255, 255, 0.58);
        }

        .task-monitoring-column-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 2px 2px 14px;
        }

        .task-monitoring-column-identity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .task-monitoring-status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.035);
        }

        .task-monitoring-column.is-pending .task-monitoring-status-dot {
            background: var(--task-pending);
        }

        .task-monitoring-column.is-ongoing .task-monitoring-status-dot {
            background: var(--task-ongoing);
        }

        .task-monitoring-column.is-late .task-monitoring-status-dot {
            background: var(--task-late);
        }

        .task-monitoring-column-title {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
        }

        .task-monitoring-column-description {
            margin: 1px 0 0;
            color: var(--task-muted);
            font-size: 10px;
        }

        .task-monitoring-column-count {
            display: grid;
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            border-radius: 9px;
            place-items: center;
            color: #62697a;
            background: #eceef4;
            font-size: 11px;
            font-weight: 800;
        }

        .task-monitoring-card-list {
            display: grid;
            gap: 11px;
        }

        .task-monitoring-card {
            position: relative;
            overflow: hidden;
            padding: 16px;
            border: 1px solid var(--task-border);
            border-radius: var(--task-radius-medium);
            background: var(--task-surface);
            box-shadow: 0 8px 22px rgba(31, 37, 68, 0.045);
            transition: border-color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
        }

        .task-monitoring-card::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            content: "";
        }

        .task-monitoring-card[data-status="pending"]::before {
            background: var(--task-pending);
        }

        .task-monitoring-card[data-status="ongoing"]::before {
            background: var(--task-ongoing);
        }

        .task-monitoring-card[data-status="late"]::before {
            background: var(--task-late);
        }

        .task-monitoring-card:hover {
            border-color: #d8dbe7;
            box-shadow: var(--task-shadow);
            transform: translateY(-2px);
        }

        .task-monitoring-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 13px;
        }

        .task-monitoring-category {
            display: inline-flex;
            align-items: center;
            min-height: 25px;
            padding: 0 9px;
            border-radius: 999px;
            color: #5f638e;
            background: #f0f1fa;
            font-size: 10px;
            font-weight: 750;
        }

        .task-monitoring-card-menu {
            display: grid;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            place-items: center;
            color: #8b91a0;
            background: transparent;
            font-size: 18px;
            line-height: 1;
        }

        .task-monitoring-card-menu:hover {
            color: var(--task-text);
            background: var(--task-surface-soft);
        }

        .task-monitoring-card h3 {
            margin: 0;
            font-size: 14px;
            line-height: 1.4;
            letter-spacing: -0.015em;
        }

        .task-monitoring-card-description {
            display: -webkit-box;
            min-height: 40px;
            margin: 7px 0 13px;
            overflow: hidden;
            color: var(--task-muted);
            font-size: 11px;
            line-height: 1.55;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .task-monitoring-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 14px;
        }

        .task-monitoring-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #73798a;
            font-size: 10px;
            font-weight: 650;
        }

        .task-monitoring-meta-item svg {
            width: 13px;
            height: 13px;
        }

        .task-monitoring-card[data-status="late"] .task-monitoring-meta-item.is-due {
            color: var(--task-late);
        }

        .task-monitoring-priority {
            margin-left: auto;
            padding: 3px 7px;
            border-radius: 7px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .task-monitoring-priority.is-high {
            color: #bd4053;
            background: var(--task-late-soft);
        }

        .task-monitoring-priority.is-medium {
            color: #966511;
            background: var(--task-pending-soft);
        }

        .task-monitoring-priority.is-low {
            color: #34745f;
            background: var(--task-success-soft);
        }

        .task-monitoring-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 9px;
            padding-top: 13px;
            border-top: 1px solid #eff0f5;
        }

        .task-monitoring-assignee {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--task-muted);
            font-size: 10px;
        }

        .task-monitoring-assignee-avatar {
            display: grid;
            width: 25px;
            height: 25px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            place-items: center;
            color: #ffffff;
            background: linear-gradient(145deg, #363a57, #5e6381);
            font-size: 8px;
            font-weight: 800;
            box-shadow: 0 0 0 1px #e2e4eb;
        }

        .task-monitoring-card-actions {
            display: flex;
            gap: 6px;
        }

        .task-monitoring-card-action {
            min-height: 28px;
            padding: 0 9px;
            border-radius: 8px;
            color: #606779;
            background: #f3f4f8;
            font-size: 9px;
            font-weight: 750;
        }

        .task-monitoring-card-action:hover {
            color: var(--task-primary);
            background: var(--task-primary-soft);
        }

        .task-monitoring-empty-state {
            display: none;
            min-height: 126px;
            padding: 24px 16px;
            border: 1px dashed #d9dce7;
            border-radius: 14px;
            color: var(--task-muted);
            background: rgba(255, 255, 255, 0.5);
            text-align: center;
        }

        .task-monitoring-empty-state.is-visible {
            display: grid;
            place-items: center;
        }

        .task-monitoring-empty-state p {
            margin: 0;
            font-size: 11px;
        }

        .task-monitoring-modal-backdrop {
            position: fixed;
            z-index: 1000;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(17, 21, 38, 0.55);
            backdrop-filter: blur(5px);
        }

        .task-monitoring-modal-backdrop.is-open {
            display: flex;
        }

        .task-monitoring-modal {
            width: min(100%, 540px);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 28px 80px rgba(17, 21, 38, 0.3);
            animation: taskMonitoringModalIn 180ms ease-out;
        }

        @keyframes taskMonitoringModalIn {
            from {
                opacity: 0;
                transform: translateY(14px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .task-monitoring-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            padding: 23px 24px 18px;
            border-bottom: 1px solid var(--task-border);
        }

        .task-monitoring-modal-header h2 {
            margin: 0;
            font-size: 20px;
            letter-spacing: -0.025em;
        }

        .task-monitoring-modal-header p {
            margin: 4px 0 0;
            color: var(--task-muted);
            font-size: 11px;
        }

        .task-monitoring-modal-close {
            display: grid;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 10px;
            place-items: center;
            color: #777d8e;
            background: #f3f4f7;
            font-size: 20px;
            line-height: 1;
        }

        .task-monitoring-form-body {
            display: grid;
            gap: 17px;
            padding: 22px 24px;
        }

        .task-monitoring-form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .task-monitoring-field {
            display: grid;
            gap: 7px;
        }

        .task-monitoring-field label {
            font-size: 11px;
            font-weight: 750;
        }

        .task-monitoring-field label span {
            color: var(--task-late);
        }

        .task-monitoring-field input,
        .task-monitoring-field select,
        .task-monitoring-field textarea {
            width: 100%;
            border: 1px solid #dfe2eb;
            border-radius: 11px;
            color: var(--task-text);
            background: #ffffff;
        }

        .task-monitoring-field input,
        .task-monitoring-field select {
            height: 43px;
            padding: 0 12px;
        }

        .task-monitoring-field textarea {
            min-height: 104px;
            padding: 11px 12px;
            resize: vertical;
        }

        .task-monitoring-field input:focus,
        .task-monitoring-field select:focus,
        .task-monitoring-field textarea:focus {
            border-color: var(--task-primary);
        }

        .task-monitoring-field-hint {
            margin: 0;
            color: #8d93a2;
            font-size: 10px;
        }

        .task-monitoring-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding: 17px 24px 23px;
            border-top: 1px solid var(--task-border);
            background: #fafbfc;
        }

        .task-monitoring-toast {
            position: fixed;
            z-index: 1100;
            right: 24px;
            bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: min(380px, calc(100vw - 32px));
            padding: 13px 16px;
            border-radius: 13px;
            color: #ffffff;
            background: #22273a;
            box-shadow: 0 18px 40px rgba(17, 21, 38, 0.24);
            opacity: 0;
            pointer-events: none;
            transform: translateY(14px);
            transition: opacity 180ms ease, transform 180ms ease;
        }

        .task-monitoring-toast.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .task-monitoring-toast-mark {
            display: grid;
            width: 25px;
            height: 25px;
            flex: 0 0 auto;
            border-radius: 50%;
            place-items: center;
            color: #ffffff;
            background: var(--task-success);
            font-size: 12px;
            font-weight: 900;
        }

        .task-monitoring-toast p {
            margin: 0;
            font-size: 11px;
            font-weight: 650;
        }

        .task-monitoring-hidden {
            display: none !important;
        }

        @media (max-width: 1180px) {
            .task-monitoring-app {
                grid-template-columns: 84px minmax(0, 1fr);
            }

            .task-monitoring-sidebar {
                align-items: center;
                padding-right: 12px;
                padding-left: 12px;
            }

            .task-monitoring-brand {
                padding-right: 0;
                padding-left: 0;
            }

            .task-monitoring-brand-text,
            .task-monitoring-nav-label,
            .task-monitoring-nav-item span,
            .task-monitoring-profile-copy-wrap,
            .task-monitoring-coming-soon {
                display: none;
            }

            .task-monitoring-nav-item {
                justify-content: center;
                width: 48px;
                padding: 0;
            }

            .task-monitoring-sidebar-footer {
                padding-right: 0;
                padding-left: 0;
            }

            .task-monitoring-overview {
                grid-template-columns: repeat(3, minmax(140px, 1fr));
            }

            .task-monitoring-focus-card {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 900px) {
            .task-monitoring-app {
                display: block;
            }

            .task-monitoring-sidebar {
                position: static;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
                height: auto;
                padding: 13px 18px;
            }

            .task-monitoring-brand {
                padding: 0;
            }

            .task-monitoring-brand-text {
                display: block;
            }

            .task-monitoring-brand-copy,
            .task-monitoring-sidebar-footer,
            .task-monitoring-nav-label,
            .task-monitoring-nav-item:not(.is-active) {
                display: none;
            }

            .task-monitoring-nav {
                align-items: center;
            }

            .task-monitoring-nav-item.is-active {
                width: auto;
                padding: 0 13px;
                box-shadow: none;
            }

            .task-monitoring-nav-item.is-active span {
                display: inline;
            }

            .task-monitoring-main {
                padding: 24px 20px 34px;
            }

            .task-monitoring-mobile-tabs {
                display: grid;
            }

            .task-monitoring-board {
                display: block;
            }

            .task-monitoring-column {
                display: none;
            }

            .task-monitoring-column.is-mobile-active {
                display: block;
            }
        }

        @media (max-width: 680px) {
            .task-monitoring-topbar {
                align-items: flex-start;
            }

            .task-monitoring-heading p,
            .task-monitoring-icon-button {
                display: none;
            }

            .task-monitoring-primary-button {
                width: 42px;
                padding: 0;
            }

            .task-monitoring-primary-button span {
                display: none;
            }

            .task-monitoring-overview {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .task-monitoring-focus-card {
                min-height: 126px;
            }

            .task-monitoring-focus-card h2 {
                font-size: 16px;
            }

            .task-monitoring-metric-card {
                display: block;
                min-height: 102px;
                padding: 13px;
            }

            .task-monitoring-metric-icon {
                width: 34px;
                height: 34px;
                margin-bottom: 10px;
            }

            .task-monitoring-metric-value {
                font-size: 19px;
            }

            .task-monitoring-metric-label {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .task-monitoring-toolbar {
                display: grid;
            }

            .task-monitoring-search {
                width: 100%;
            }

            .task-monitoring-form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 430px) {
            .task-monitoring-main {
                padding-right: 14px;
                padding-left: 14px;
            }

            .task-monitoring-overview {
                gap: 8px;
            }

            .task-monitoring-focus-card {
                padding: 18px;
            }

            .task-monitoring-progress-ring {
                width: 58px;
                height: 58px;
            }

            .task-monitoring-metric-card {
                padding: 11px;
            }

            .task-monitoring-modal-backdrop {
                align-items: flex-end;
                padding: 0;
            }

            .task-monitoring-modal {
                width: 100%;
                max-height: 92vh;
                border-radius: 22px 22px 0 0;
            }

            .task-monitoring-toast {
                right: 16px;
                bottom: 16px;
                left: 16px;
            }
        }
    </style>
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
                        <p class="task-monitoring-metric-value"><?php echo $taskMonitoringActiveCount; ?></p>
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
                        <p class="task-monitoring-metric-value"><?php echo $taskMonitoringDueTodayCount; ?></p>
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
                        <p class="task-monitoring-metric-value"><?php echo $taskMonitoringCompletedCount; ?></p>
                        <p class="task-monitoring-metric-label">Completed</p>
                    </div>
                </article>
            </section>

            <section id="taskMonitoringBoard" aria-labelledby="taskMonitoringBoardTitle">
                <div class="task-monitoring-toolbar">
                    <div class="task-monitoring-section-title">
                        <h2 id="taskMonitoringBoardTitle">My task board</h2>
                        <p>Late tasks are detected automatically from their due date.</p>
                    </div>
                    <label class="task-monitoring-search" for="taskMonitoringSearch">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="M20 20l-4-4"></path>
                        </svg>
                        <input id="taskMonitoringSearch" type="search" placeholder="Search tasks" autocomplete="off">
                    </label>
                </div>

                <div class="task-monitoring-mobile-tabs" aria-label="Task statuses">
                    <?php foreach ($taskMonitoringColumns as $taskMonitoringStatusKey => $taskMonitoringColumn) { ?>
                        <button
                            class="task-monitoring-tab<?php echo $taskMonitoringStatusKey === 'pending' ? ' is-active' : ''; ?>"
                            type="button"
                            data-mobile-status="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                            <?php echo htmlspecialchars($taskMonitoringColumn['label'], ENT_QUOTES, 'UTF-8'); ?>
                            (<?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]); ?>)
                        </button>
                    <?php } ?>
                </div>

                <div class="task-monitoring-board">
                    <?php foreach ($taskMonitoringColumns as $taskMonitoringStatusKey => $taskMonitoringColumn) { ?>
                        <section
                            class="task-monitoring-column is-<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?><?php echo $taskMonitoringStatusKey === 'pending' ? ' is-mobile-active' : ''; ?>"
                            data-task-column="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>"
                            aria-labelledby="taskMonitoringColumn<?php echo ucfirst($taskMonitoringStatusKey); ?>"
                        >
                            <header class="task-monitoring-column-header">
                                <div class="task-monitoring-column-identity">
                                    <span class="task-monitoring-status-dot" aria-hidden="true"></span>
                                    <div>
                                        <h3 class="task-monitoring-column-title" id="taskMonitoringColumn<?php echo ucfirst($taskMonitoringStatusKey); ?>">
                                            <?php echo htmlspecialchars($taskMonitoringColumn['label'], ENT_QUOTES, 'UTF-8'); ?>
                                        </h3>
                                        <p class="task-monitoring-column-description">
                                            <?php echo htmlspecialchars($taskMonitoringColumn['description'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="task-monitoring-column-count" data-column-count="<?php echo htmlspecialchars($taskMonitoringStatusKey, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]); ?>
                                </span>
                            </header>

                            <div class="task-monitoring-card-list">
                                <?php foreach ($taskMonitoringGroupedTasks[$taskMonitoringStatusKey] as $taskMonitoringTask) { ?>
                                    <?php
                                    $taskMonitoringCardDueDate = new DateTimeImmutable($taskMonitoringTask['DueDate']);

                                    if ($taskMonitoringCardDueDate == $taskMonitoringToday) {
                                        $taskMonitoringDueLabel = 'Due today';
                                    } elseif ($taskMonitoringCardDueDate == $taskMonitoringToday->modify('+1 day')) {
                                        $taskMonitoringDueLabel = 'Due tomorrow';
                                    } elseif ($taskMonitoringCardDueDate < $taskMonitoringToday) {
                                        $taskMonitoringLateDays = (int)$taskMonitoringCardDueDate->diff($taskMonitoringToday)->format('%a');
                                        $taskMonitoringDueLabel = $taskMonitoringLateDays . ($taskMonitoringLateDays === 1 ? ' day late' : ' days late');
                                    } else {
                                        $taskMonitoringDueLabel = 'Due ' . $taskMonitoringCardDueDate->format('M j');
                                    }
                                    ?>
                                    <article
                                        class="task-monitoring-card"
                                        data-task-card
                                        data-task-id="<?php echo (int)$taskMonitoringTask['TaskID']; ?>"
                                        data-status="<?php echo htmlspecialchars($taskMonitoringTask['DisplayStatus'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-stored-status="<?php echo htmlspecialchars($taskMonitoringTask['TaskStatus'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-title="<?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-description="<?php echo htmlspecialchars($taskMonitoringTask['TaskDescription'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-due-date="<?php echo htmlspecialchars($taskMonitoringTask['DueDate'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-category="<?php echo htmlspecialchars($taskMonitoringTask['Category'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-priority="<?php echo htmlspecialchars($taskMonitoringTask['Priority'], ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                        <div class="task-monitoring-card-top">
                                            <span class="task-monitoring-category">
                                                <?php echo htmlspecialchars($taskMonitoringTask['Category'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <button
                                                class="task-monitoring-card-menu"
                                                type="button"
                                                aria-label="Edit <?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-task-edit
                                            >
                                                ···
                                            </button>
                                        </div>
                                        <h3><?php echo htmlspecialchars($taskMonitoringTask['TaskTitle'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p class="task-monitoring-card-description">
                                            <?php echo htmlspecialchars($taskMonitoringTask['TaskDescription'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <div class="task-monitoring-card-meta">
                                            <span class="task-monitoring-meta-item is-due">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                                    <path d="M16 3v4M8 3v4M3 10h18"></path>
                                                </svg>
                                                <?php echo htmlspecialchars($taskMonitoringDueLabel, ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <span class="task-monitoring-meta-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="9"></circle>
                                                    <path d="M12 7v5l3 2"></path>
                                                </svg>
                                                <?php echo htmlspecialchars($taskMonitoringTask['Estimate'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <span class="task-monitoring-priority is-<?php echo strtolower(htmlspecialchars($taskMonitoringTask['Priority'], ENT_QUOTES, 'UTF-8')); ?>">
                                                <?php echo htmlspecialchars($taskMonitoringTask['Priority'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                        <footer class="task-monitoring-card-footer">
                                            <span class="task-monitoring-assignee">
                                                <span class="task-monitoring-assignee-avatar">AJ</span>
                                                You
                                            </span>
                                            <span class="task-monitoring-card-actions">
                                                <?php if ($taskMonitoringTask['TaskStatus'] === 'PENDING') { ?>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Move to Ongoing">Start</button>
                                                <?php } else { ?>
                                                    <button class="task-monitoring-card-action" type="button" data-prototype-action="Move to Pending">Pause</button>
                                                <?php } ?>
                                                <button class="task-monitoring-card-action" type="button" data-prototype-action="Complete Task">Complete</button>
                                            </span>
                                        </footer>
                                    </article>
                                <?php } ?>

                                <div class="task-monitoring-empty-state<?php echo count($taskMonitoringGroupedTasks[$taskMonitoringStatusKey]) === 0 ? ' is-visible' : ''; ?>" data-empty-state>
                                    <p>No <?php echo strtolower(htmlspecialchars($taskMonitoringColumn['label'], ENT_QUOTES, 'UTF-8')); ?> tasks to show.</p>
                                </div>
                            </div>
                        </section>
                    <?php } ?>
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

    <script>
        var taskMonitoringToastTimer = null;
        var taskMonitoringLastFocusedElement = null;
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

        taskMonitoringBindEvent('click.taskMonitoringAdd', '#btnTaskMonitoringAdd', function () {
            task_monitoring_open_add();
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

        //===================================================================================================
        // ALL FUNCTIONS RELATED TO TASK MONITORING
        //===================================================================================================

        function task_monitoring_open_add() {
            var form = document.getElementById('taskMonitoringForm');
            var modal = document.getElementById('taskMonitoringModal');

            taskMonitoringLastFocusedElement = document.activeElement;
            form.reset();
            form.setAttribute('operation', '0');
            form.setAttribute('recID', '');
            document.getElementById('taskMonitoringModalTitle').textContent = 'Add a new task';
            document.getElementById('taskMonitoringSubmitText').textContent = 'Save Task';
            document.getElementById('taskMonitoringDueDate').value = '<?php echo $taskMonitoringToday->format('Y-m-d'); ?>';
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

            document.querySelectorAll('[data-task-column]').forEach(function (taskColumn) {
                var visibleCards = taskColumn.querySelectorAll('[data-task-card]:not(.task-monitoring-hidden)').length;
                var emptyState = taskColumn.querySelector('[data-empty-state]');
                var status = taskColumn.getAttribute('data-task-column');
                var countElement = document.querySelector('[data-column-count="' + status + '"]');

                emptyState.classList.toggle('is-visible', visibleCards === 0);
                countElement.textContent = visibleCards;
            });
        }

        function task_monitoring_change_mobile_tab(status) {
            document.querySelectorAll('[data-mobile-status]').forEach(function (tabButton) {
                tabButton.classList.toggle('is-active', tabButton.getAttribute('data-mobile-status') === status);
            });

            document.querySelectorAll('[data-task-column]').forEach(function (taskColumn) {
                taskColumn.classList.toggle('is-mobile-active', taskColumn.getAttribute('data-task-column') === status);
            });
        }
    </script>
</body>
</html>
