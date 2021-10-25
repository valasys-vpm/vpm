
function checkSession(e){401==e.status&&location.reload()}

$(".alert-auto-dismiss").fadeTo(5000,500).slideUp(500,function(){$(".alert-auto-dismiss").slideUp(500)});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
    $('.double-click').click(function() {
        return false;
    }).dblclick(function() {
        window.location = this.href;
        return false;
    });
});

//Idle Time Functionality
    // Set timeout variables.
    let timoutWarning = 840000; // Display warning in 14 Mins.
    let timoutNow = 900000; // Timeout in 15 mins.
    let logoutUrl = $('meta[name="base-path"]').attr('content') + '/logout'; // URL to logout page.

    let warningTimer;
    let timeoutTimer;

    // Start timers.
    function StartTimers() {
        warningTimer = setTimeout("IdleWarning()", timoutWarning);
        timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
    }

    // Reset timers.
    function ResetTimers() {
        clearTimeout(warningTimer);
        clearTimeout(timeoutTimer);
        StartTimers();
        //$("#timeout").dialog('close');
    }

    // Show idle timeout warning dialog.
    function IdleWarning() {
        alert('Warning: No activity detected, session will be end soon.');
    }

    // Logout the user.
    function IdleTimeout() {
        window.location = logoutUrl;
    }

    $(function () {
        //StartTimers();
        $('body').mousemove(function() {
            ResetTimers();
        });

        $('body').keypress(function() {
            ResetTimers();
        });
    });
//Idle Time Functionality

/* --- Pnotify Custom Js --- */
function trigger_pnofify(type = 'default', title = '', message = '') {
    new PNotify({
        title: (title === '') ? false : title,
        text: (message === '') ? false : message,
        type: type,
        icon: (title === '') ? 'none' : true,
        buttons: {
            sticker: false
        },
        delay: 5000
    });

}
/* --- END - Pnotify Custom Js --- */

function downloadSampleFile(file_name) {
    return window.location.href = $('meta[name="base-path"]').attr('content') + '/public/storage/sample/' + file_name;
}


