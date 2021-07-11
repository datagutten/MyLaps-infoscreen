let interval_id;
$(document).ready(function () {
    console.log('Page load');
    enable_refresh()
    update()
});

function update() {
    const urlParams = new URLSearchParams(window.location.search);
    const decoder = urlParams.get('decoder');
    $("#laps").load("infoscreen.php?decoder=" + decoder);
}

function disable_refresh() {
    clearInterval(interval_id);
    $("#disable_refresh").html('Refresh disabled, click to enable').on('click', enable_refresh)
}

function enable_refresh() {
    const interval = $('#laps')[0].getAttribute('data-refresh')
    console.log('Refresh every', interval, 'second(s)')
    interval_id = setInterval(update, interval * 1000);

    $("#disable_refresh").html('Disable refresh').on('click', () => {
        disable_refresh()
    })
}