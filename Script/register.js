$("#register-form").submit(function() {
    let form_data = new FormData($(this)[0]);
    let town_id =$("#city option:selected").attr("data-id");
    form_data.append("town",  town_id);
    $.ajax({
        url: "/create-user",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        data : form_data,
        success : function(result) {
            printMessage("success",result.message,5000);
            $(location).attr("href","/");

        },
        error : function(result) {
            printMessage("error",result.responseJSON.message,5000);

        }
    })
    return false;
})