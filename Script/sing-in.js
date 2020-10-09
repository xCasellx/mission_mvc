$("#login-form").submit(function() {
    let form_data = new FormData($(this)[0]);
    $.ajax({
        url: "/login-user",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        data : form_data,
        success : function(result) {
            $(location).attr("href","/cabinet");
            printMessage("success",result.message,5000);
        },
        error : function(result) {
           
            printMessage("error",result.responseJSON.message,5000);
        }
    })
    return false;
})