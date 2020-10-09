let user_data;

$(document).ready(function () {
    $.ajax({
        url: "/validate",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        success : function(result) {
            let  data = result.data;
            user_data = data;
            $("#user-image").attr("src", data.image);
            $("#user_first_name").text(data.first_name);
            $("#user_second_name").text(data.second_name);
            $("#user_number").text(data.number);
            $("#user_date").text(data.date);
            $("#user_town").text(data.city+","+data.region+","+data.country);
            $("#user_email").text(data.email);
            if( data.email_activate === "0") {
                let html = `
                      <div class="alert-danger p-2" id="main-message">
                        <a href="#" class="p-3 text-decoration-none text-danger" id="email-confirm">Click to confirm mail</a>  
                      </div>`;
                $("main").prepend(html);
            }
        },
        error : function(result) {

        }
    })
})



$(document).on("click", "#email-confirm", function () {
    $( document ).off( "click", "#email-confirm" );
    $.ajax({
        url: "/email/send/verify",
        type : "POST",
        success : function(result) {
            $("#main-message").html(result.message);
        },
        error : function(result) {
            $("#main-message").html("A message has been sent to the mail,failed to send message");
        }
    })
});


$("#user-image").error(function() {
    $(this).attr('src', '../image/nan.png?');
});

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

let edit_component;

$(".edit-data").on("click",function (){
    let html = `  <div class="container" align="center">
                            <div class="status-message d-none text-center mt-2 p-2"></div>
                            <form action="/cabinet" id="edit-form" method="post">
                                <div id="form-content" class="m-4"></div>
                                <button type="submit"  id="save_button" class="btn btn-outline-success" ><strong>Save</strong></button>
                                <button type="button"  class="btn btn-outline-danger" data-dismiss="modal"><strong>Close</strong></button>
                            </form>
                        </div>`
    $(".modal-body").html(html);
})

$("#edit-second_name").on("click", function () {
    edit_component = $(this).attr('id').replace("edit-","");
    let html=`<input required type="text" class="form-control border-dark border input-text input-edit" 
              id="input-second_name" name="edit_text" placeholder='` +user_data.second_name+ `'">`
    $("#form-content").html(html);
    $(".modal-title").text("Edit second name");
});
$("#edit-first_name").on("click", function () {
    edit_component = $(this).attr('id').replace("edit-", "");
    let html=`<input required type="text" class="form-control border-dark border input-text input-edit" 
              id="input-first_name" name="edit_text" placeholder='` +user_data.first_name+ `'">`
    $("#form-content").html(html);
    $(".modal-title").text("Edit first name");
});
$("#edit-number").on("click", function () {
    edit_component = $(this).attr('id').replace("edit-", "");
    let html=`<input required type="text" name="edit_text"  class="form-control border-dark border input-text input-edit" 
              id="input-number" placeholder='` +user_data.number+ `' pattern="[0-9]{10,15}">`
    $("#form-content").html(html);
    $(".modal-title").text("Edit number");
});

$("#edit-date").on("click",function () {
    edit_component = $(this).attr('id').replace("edit-", "");
    let html=`<input required type="date" name="edit_text"  class="form-control border-dark border input-text input-edit" 
             value="`+user_data.date+`" id="input-date" max='` +maxDate(8)+ `'">`
    $("#form-content").html(html);
    $(".modal-title").text("Edit date");
});

$("#edit-town").on("click",function () {
    loadListCountry();
    edit_component = $(this).attr('id').replace("edit-","");
    let html=`<select required id="country" class="border border-dark col mt-1 custom-select"></select>
            <select required id="region" class="border border-dark col mt-1 custom-select"></select>
            <select required id="city" class="border border-dark col mt-1 custom-select"></select>`
    $("#form-content").html(html);
    $(".modal-title").text("Edit town");
});
$(document).on("change","#country", function () {
    $("#city").empty();
    loadListRegion();
});

$(document).on("change","#region", function () {
    loadListCity();
});

$("#edit-email").on("click",function () {
    edit_component = $(this).attr('id').replace("edit-","");
    let html=`<input required type="password" name="password" class="mt-2 input-text border-dark border form-control" placeholder="Password"  id="input-password">
              <input required type="email" name="edit_text"  class="mt-2 form-control border-dark border input-text input-edit" 
              id="input-email" placeholder='`+user_data.email+`'">`
    $("#form-content").html(html);
    $(".modal-title").text("Edit email");
});

$("#edit-image").on("click",function () {
    edit_component = $(this).attr('id').replace("edit-", "");
    let html = `  <div class="container" align="center">
                            <div class="status-message d-none text-center mt-2 p-2"></div>
                            <form enctype="multipart/form-data" action="/cabinet" id="edit-form-image" method="post">
                                <div id="form-content" class="m-4">
                                    <input required name='image' type="file" class="input-edit input-text" id="input-image" accept="image/jpeg,image/png,image/gif">
                                </div>
                                <button type="submit"  id="save_button" class="btn btn-outline-success" ><strong>Save</strong></button>
                                <button type="button"  class="btn btn-outline-danger" data-dismiss="modal"><strong>Close</strong></button>
                            </form>
                        </div>`
    $(".modal-body").html(html);
    $(".modal-title").text("Edit image");

});

$("#edit-password").on("click",function () {
    edit_component = $(this).attr('id').replace("edit-", "");
    let html=`<input required type="password" name="password" class="mt-2 input-text border-dark border form-control" placeholder="Password"  id="input-password">
              <input required type="password" name="edit_text" class="mt-2 input-text border-dark border form-control" placeholder="New password"  id="input-new-password">
              <input required type="password" name="confirm_password" class="mt-2 input-text border-dark border form-control" placeholder="Confirm password" id="input-confirm-password">`
    $("#form-content").html(html);
    $(".modal-title").text("Edit password");
});

$(document).on("submit","#edit-form",function () {
        let form;
        if(edit_component === "town") {
            form = new FormData();
            let town_id =$("#city option:selected").attr("data-id");
            form.append("edit_text",  town_id);
        }
        else {
            form = new FormData($(this)[0]);
        }
        form.append("edit_name",  edit_component);

    $.ajax({
        url: "/update-user",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        data : form,
        success : function(result){
            console.log(result.message);
            printMessage("success", result.message, 6000);
            let  data = result.data;
            user_data = data;
            $("#user_first_name").text(data.first_name);
            $("#user_second_name").text(data.second_name);
            $("#user_number").text(data.number);
            $("#user_date").text(data.date);
            $("#user_town").text(data.city+","+data.region+","+data.country);
            $("#user_email").text(data.email);
            $('#myModal').modal('hide');
        },
        error : function(result){
            console.log(result);
            printMessage("error",result.responseJSON.message, 5000);
        }
    })
    return false;

})

$(document).on("submit","#edit-form-image",function () {
    let form_data = new FormData($(this)[0]);
    $.ajax({
        url: "/update-image",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data:form_data,
        success:function (result){
            user_data.image = result.data.image;
            $("#user-image").attr("src", user_data.image+"?"+Math.random());
            $('#myModal').modal('hide');
        },
        error:function (result){
            console.log(result);
            printMessage("error", result.responseJSON.message, 5000);
        }
    });
    return false;
});

