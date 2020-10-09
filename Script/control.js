function maxDate (year) {
    let d = new Date();
    d.setTime(d.getTime());
    let s = (d.getFullYear()-year)+"-12-01";
    return s;
}
function  printMessage(type, text , time) {
    $(".status-message").removeClass( "d-none" );
    if ("success" === type) {
        $("#status-message").addClass("alert-success");
    }
    else if ("error" === type) {
        $(".status-message").addClass("alert-danger");
    }
    $(".status-message").text(text);
    if(time !== null ) {
        setTimeout(deleteMessage, time);
    }
}

function  deleteMessage() {
    $(".status-message").addClass( "d-none" );
    $(".status-message").text("");
    if ($(".status-message").hasClass( "alert-danger" )) {
        $(".status-message").removeClass( "alert-danger" );
        return;
    }
    if ($(".status-message").hasClass( "alert-success" )) {
        $(".status-message").removeClass( "alert-success" );
        return;
    }
}

