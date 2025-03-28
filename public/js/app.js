var sidebarOverlay = document.getElementById('sidebar-overlay');

if (sidebarOverlay) {
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('sidebar-minified');
        sidebarOverlay.classList.toggle('show');
    }

    sidebarOverlay.addEventListener('click', function () {
        document.getElementById('sidebar').classList.remove('sidebar-minified');
        sidebarOverlay.classList.toggle('show');
    });
}

/*======== 8. LOADING BUTTON ========*/
/* 8.1. BIND NORMAL BUTTONS */
Ladda.bind(".ladda-button", {
    timeout: 5000
});

/* 7.2. BIND PROGRESS BUTTONS AND SIMULATE LOADING PROGRESS */
Ladda.bind(".progress-demo button", {
    callback: function (instance) {
        var progress = 0;
        var interval = setInterval(function () {
            progress = Math.min(progress + Math.random() * 0.1, 1);
            instance.setProgress(progress);

            if (progress === 1) {
                instance.stop();
                clearInterval(interval);
            }
        }, 200);
    }
});

function toggleSubMenu(e) {
    e.parentElement.classList.toggle('has-active');
}
