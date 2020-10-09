let hash;
$(document).ready(function () {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    hash = urlParams.get('hash');
    if(hash === null) {
        printMessage("error", "Empty parameter" , null);
        return false;
    }
})

$(document).on("submit", "#form-recovery", function () {
    let form = new FormData($(this)[0]);
    form.append("hash",hash);
    $.ajax({
        url: "/password/recovery",
        type : "POST",
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        success : function(result) {
            printMessage("success", result.message, null);
            $(location).attr("href", "/");
        },
        error : function(result) {
            console.log(result);
            printMessage("error",result.responseJSON.message,null);
        }
    })
    return false;
})
