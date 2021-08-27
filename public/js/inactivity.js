// Auto-Logout Handler
function startHandler(timeout, url_root) {
    let minutes = timeout;
    let countDownDate = new Date().getTime() + minutes * 60000;
    let difference = 0;

    document.addEventListener('click', function (event) {
        countDownDate = countDownDate + difference*1000;
        difference = 0;
    }, false);

    const x = setInterval(function () {
        difference += 1;
        const left = Math.round((((minutes * 60000) / 1000) - difference) / 60);
        if (left == 0) {
            clearInterval(x);
            window.location.href = url_root + '/users/logout';
        }
    }, 1000);
}