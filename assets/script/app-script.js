//===================================================================================================
// MODULE VARIABLES
//===================================================================================================

var appModuleFiles = {
    dashboard: 'modules/dashboard/dashboard-main.php',
    tasks: 'modules/tasks/tasks-main.php'
};
var appModuleNames = { dashboard: 'Dashboard', tasks: 'Tasks' };
var appModuleActive = '';
var appModuleRequested = '';
var appModuleNavigationPending = false;
var appModuleLoadingPage = false;
var appModuleRequest = null;
var appModuleComponentRequests = [];
var appModuleSequence = 0;
var appModuleLoaderGuardTimer = null;
var appModuleLoaderHideTimer = null;

// Shared delegated event binding for module scripts.
if (typeof window.bindEvent !== 'function') {
    window.bindEvent = function (eventName, selector, callback) {
        $(document).off(eventName, selector).on(eventName, selector, function (event) {
            if (event.type === 'submit') {
                event.preventDefault();
            }

            return callback.call(this, event);
        });
    };
}

//===================================================================================================
// BIND EVENTS
//===================================================================================================

if (typeof window.jQuery === 'function') {
    $(document).ready(function () {
        app_module_load(app_module_from_location(), false);
    });

    bindEvent('click.appModuleNavigation', '[data-module-link]', function (event) {
        app_module_navigate(this, event);
    });

    bindEvent('click.appModuleRetry', '[data-module-retry]', function () {
        app_module_load(appModuleRequested, true);
    });

    $(window).on('popstate.appModuleNavigation hashchange.appModuleNavigation', function () {
        var moduleName = app_module_from_location();

        if (moduleName === appModuleRequested && (appModuleNavigationPending || appModuleLoadingPage)) {
            return;
        }

        app_module_load(moduleName, false);
    });
}

//===================================================================================================
// SHARED FUNCTIONS
//===================================================================================================

function app_module_from_location() {
    var moduleName = window.location.hash.slice(1);

    return Object.prototype.hasOwnProperty.call(appModuleFiles, moduleName) ? moduleName : 'dashboard';
}

function app_module_loader_show(message) {
    var loader = document.getElementById('appModuleLoader');
    var content = document.getElementById('appModuleContent');

    window.clearTimeout(appModuleLoaderHideTimer);
    window.clearTimeout(appModuleLoaderGuardTimer);
    document.getElementById('appModuleLoaderText').textContent = message;
    loader.hidden = false;
    loader.classList.remove('is-finished');
    document.body.classList.add('is-module-loading');
    document.body.classList.remove('is-module-ready');
    content.inert = true;
    content.setAttribute('aria-busy', 'true');

    // The sidebar stays usable even if an individual component stalls.
    appModuleLoaderGuardTimer = window.setTimeout(app_module_loader_hide, 10000);
}

function app_module_loader_hide() {
    var loader = document.getElementById('appModuleLoader');
    var content = document.getElementById('appModuleContent');
    var focusHeading = appModuleNavigationPending;

    window.clearTimeout(appModuleLoaderGuardTimer);
    window.clearTimeout(appModuleLoaderHideTimer);
    appModuleNavigationPending = false;
    document.body.classList.remove('is-module-loading');
    document.body.classList.add('is-module-ready');
    content.inert = false;
    content.setAttribute('aria-busy', 'false');
    loader.classList.add('is-finished');

    if (focusHeading && !appModuleLoadingPage) {
        var heading = content.querySelector('h1');

        if (heading) {
            heading.setAttribute('tabindex', '-1');
            heading.focus({ preventScroll: true });
        }
    }

    appModuleLoaderHideTimer = window.setTimeout(function () {
        loader.hidden = true;
    }, window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 180);
}

function app_module_component_ready(containerID) {
    var content = document.getElementById('appModuleContent');
    var container = document.getElementById(containerID);

    if (!container || !container.hasAttribute('data-module-component') || container.hasAttribute('data-module-ready')) {
        return;
    }

    container.setAttribute('data-module-ready', 'true');

    if (!appModuleLoadingPage && !content.querySelector('[data-module-component]:not([data-module-ready])')) {
        app_module_loader_hide();
    }
}

function app_module_navigate(link, event) {
    if (event.isDefaultPrevented() || event.button > 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey
        || link.hasAttribute('download') || (link.target && link.target !== '_self')) {
        return;
    }

    var moduleName = link.getAttribute('data-module');

    if (!Object.prototype.hasOwnProperty.call(appModuleFiles, moduleName)) {
        return;
    }

    event.preventDefault();
    app_module_load(moduleName, true);
}

function app_module_load(moduleName, updateHistory) {
    if (!Object.prototype.hasOwnProperty.call(appModuleFiles, moduleName)
        || (moduleName === appModuleActive && !appModuleNavigationPending && !appModuleLoadingPage)) {
        return;
    }

    var sequence = ++appModuleSequence;
    appModuleRequested = moduleName;
    appModuleLoadingPage = true;
    appModuleNavigationPending = true;
    document.getElementById('appModuleError').hidden = true;
    app_module_loader_show('Opening ' + appModuleNames[moduleName] + '...');

    if (appModuleRequest && appModuleRequest.readyState !== 4) {
        appModuleRequest.abort();
    }

    appModuleComponentRequests.forEach(function (request) {
        if (request && request.readyState !== 4) {
            request.abort();
        }
    });
    appModuleComponentRequests = [];

    $('#taskMonitoringModal, #taskMonitoringDetailsModal').removeClass('is-open').attr('aria-hidden', 'true');
    document.body.style.overflow = '';

    appModuleRequest = $.ajax({
        url: appModuleFiles[moduleName],
        type: 'POST',
        dataType: 'html',
        timeout: 10000
    }).done(function (html) {
        if (sequence !== appModuleSequence) {
            return;
        }

        $('#appModuleContent').html(html);
        document.body.setAttribute('data-task-page', moduleName);
        document.title = appModuleNames[moduleName] + ' | Task Monitoring';
        appModuleActive = moduleName;
        appModuleLoadingPage = false;

        document.querySelectorAll('[data-module-link]').forEach(function (link) {
            var isActive = link.getAttribute('data-module') === moduleName;

            link.classList.toggle('is-active', isActive);

            if (isActive) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });

        if (updateHistory && window.location.hash !== '#' + moduleName) {
            window.history.pushState({ module: moduleName }, '', '#' + moduleName);
        }

        window.scrollTo(0, 0);
        appModuleComponentRequests = [tasks_init()];

        if (moduleName === 'dashboard') {
            appModuleComponentRequests = appModuleComponentRequests.concat(dashboard_init());
        }
    }).fail(function (xhr, status) {
        if (status === 'abort' || sequence !== appModuleSequence) {
            return;
        }

        appModuleLoadingPage = false;
        document.getElementById('appModuleErrorText').textContent = 'Could not open ' + appModuleNames[moduleName] + '. Please try again.';
        document.getElementById('appModuleError').hidden = false;
        app_module_loader_hide();
    });
}
