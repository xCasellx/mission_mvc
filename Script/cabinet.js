$(document).ready(function () {
    $.ajax({
        url: "/validate",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        success : function(result) {
           let  data = result.data;
            $("#user_first_name").text(data.first_name);
            $("#user_second_name").text(data.second_name);
            $("#user_number").text(data.number);
            $("#user_date").text(data.date);
            $("#user_town").text(data.city+","+data.region+","+data.country);
            $("#user_email").text(data.email);
        },
        error : function(result) {

        }
    })
})

$(document).on("click","#open-edit-data",function () {
    $(".edit-data").toggleClass("d-none");
    $("#edit-image").toggleClass("d-none");
    if ($("#open-edit-data").text()=="Edit") {
        $("#open-edit-data").text("Cancel");
    }
    else {
        $("#open-edit-data").text("Edit");
    }
});


