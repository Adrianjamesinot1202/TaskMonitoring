//===================================================================================================
// MODULE VARIABLES
//===================================================================================================

var appToastTimer = null;

//===================================================================================================
// SHARED NOTIFICATION FUNCTIONS
//===================================================================================================

function showToast(message, type) {
    var toast = document.getElementById('appToast');
    var toastMessage = document.getElementById('appToastMessage');
    var toastMark = document.getElementById('appToastMark');
    var marks = { success: '✓', warning: '!', error: '×', info: 'i' };

    if (!toast || !toastMessage || !toastMark) {
        return;
    }

    type = Object.prototype.hasOwnProperty.call(marks, type) ? type : 'info';
    window.clearTimeout(appToastTimer);
    toast.setAttribute('data-toast-type', type);
    toastMessage.textContent = String(message || '');
    toastMark.textContent = marks[type];
    toast.classList.add('is-visible');

    appToastTimer = window.setTimeout(function () {
        toast.classList.remove('is-visible');
    }, type === 'error' || type === 'warning' ? 6000 : 3200);
}

function handleAjaxResponse(response, onSuccess, actionType, modalSelector) {
    var parsedResponse;
    var successMessages = {
        save: 'Record saved successfully.',
        update: 'Record updated successfully.',
        delete: 'Record deleted successfully.',
        post: 'Record posted successfully.',
        send: 'Sent successfully.',
        approve: 'Approved successfully.',
        reject: 'Rejected successfully.'
    };

    try {
        try {
            parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (parseError) {
            parsedResponse = null;
        }

        var isSuccess = parsedResponse && parsedResponse.status === 'success';

        if (isSuccess) {
            if (typeof onSuccess === 'function') {
                onSuccess(parsedResponse);
            }

            if (String(actionType || 'save').toLowerCase() !== 'select') {
                showToast(parsedResponse.msg || successMessages[actionType] || successMessages.save, 'success');
            }

            return;
        }

        if (modalSelector) {
            $(modalSelector).each(function () {
                if (window.bootstrap && window.bootstrap.Modal) {
                    var modalInstance = window.bootstrap.Modal.getInstance(this);

                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }

                this.classList.remove('is-open');
                this.setAttribute('aria-hidden', 'true');
            });
            document.body.style.overflow = '';
        }

        if (typeof response === 'string' && /<!doctype|<html|<script/i.test(response)
            && typeof window.AlertSessionExpired === 'function') {
            window.AlertSessionExpired();
            return;
        }

        var message = parsedResponse && typeof parsedResponse.msg === 'string'
            ? parsedResponse.msg
            : 'Unable to read the server response. Please try again.';
        showToast(message, parsedResponse && parsedResponse.status === 'warning' ? 'warning' : 'error');
    } finally {
        if (typeof window.endProcessLoader === 'function') {
            window.endProcessLoader();
        }
    }
}

function handleAjaxError(xhr, status, error) {
    try {
        if (status === 'abort') {
            return;
        }

        var response = xhr && xhr.responseJSON;
        var responseText = xhr && typeof xhr.responseText === 'string' ? xhr.responseText : '';

        if (!response && responseText) {
            try {
                response = JSON.parse(responseText);
            } catch (parseError) {
                response = null;
            }
        }

        if (/<!doctype|<html|<script/i.test(responseText) && typeof window.AlertSessionExpired === 'function') {
            window.AlertSessionExpired();
            return;
        }

        if (response && typeof response.msg === 'string') {
            showToast(response.msg, response.status === 'warning' ? 'warning' : 'error');
        } else {
            showToast(status === 'timeout'
                ? 'The request timed out. Please try again.'
                : 'Unable to complete the request. Please try again.', 'error');
        }
    } finally {
        if (typeof window.endProcessLoader === 'function') {
            window.endProcessLoader();
        }
    }
}
