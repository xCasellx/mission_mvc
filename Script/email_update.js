$(document).ready(function () {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let hash = urlParams.get('hash');
    if(hash === null) {
        printMessage("error", "Empty parameter" , null);
        return false;
    }
    $.ajax({
        url: "/email/user/update",
        type : "GET",
        data: {hash : hash},
        success : function(result) {
            console.log(result);
            printMessage("success", result.message, null);
        },
        error : function(result) {
            console.log(result);
            printMessage("error",result.responseJSON.message,null);
        }
    })
})
