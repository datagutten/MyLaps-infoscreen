let interval_id;
$( document ).ready(function() {
    console.log('Page load');
    $.get('get_config.php', config_handler, 'json');
    update()
});

function config_handler(data) {
    console.log(data);
    set_refresh(data['refresh_interval']);
    $("title").html( data['title']);
    if(data['disable_button']) {
        $("#disable_refresh").removeAttr('style');
    }
}

function update() {
    const urlParams = new URLSearchParams(window.location.search);
    const decoder = urlParams.get('decoder');
    $("#laps").load("infoscreen.php?decoder=" + decoder);
}

function set_refresh(interval) {
    interval_id = setInterval(update,interval*1000);
}

function disable_refresh() {
    clearInterval(interval_id);
    $("#disable_refresh").html('Refresh disabled')
}