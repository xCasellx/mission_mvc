let user_data;
$(document).ready(function () {
    $.ajax({
        url: "/validate",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        success : function(result) {
            user_data = result.data;
            $.ajax({
                url: "/comments/load",
                type : "POST",
                cache: false,
                contentType: false,
                processData: false,
                success : function(result) {
                    let comment = result.data;
                    comment.forEach(element => {
                        PrintComment(element);
                        $(".comment-img").error(function() {
                            $(this).attr('src', '../image/nan.png');
                        });
                    })
                },
                error : function(result) {
                   console.log(result)
                }
            })
        }
    })

})

function PrintComment(comment) {
    let edit = (comment.edit_check === "1") ? "(edited) " : "";
    let config_p = ( comment.user_id === user_data.id ) ? `
        <a class="text-warning text-decoration-none edit-comment" href="#"  data-id = "` + comment.id + `" data-toggle='modal' data-target='#myModal'><strong>edit</strong></a>
        <a class="text-danger text-decoration-none delete-comment " href="#" data-id = "` + comment.id + `" data-toggle='modal' data-target='#myModal'><strong>delete</strong></a>` : ``;
    let img = (comment.image !== null)? comment.image : "/api/image/nan.png"
    let html =`
                <div class = 'card p-0 comments' data-id ="` +comment.id + `">
                    <div class =' p-1 card-header bg-dark text-light row' >
                        <div class ='col-1 p-0' style="max-width: 32px">
                            <img class ='comment-img rounded m-0 img-fluid img' src='`+img+`' style='width: 32px;height: 32px;' alt=''>
                        </div>
                        <h6 class='col '>`+ comment.first_name +` `+comment.second_name+`</h6>
                        <small class='col text-right date-comment' data-id="` + comment.id + `" ><lable>`+ edit +`</lable> `+ comment.date +`</small>
                    </div>
                    <div class='p-1 card-body comments-text' data-id="` + comment.id + `">
                       `+comment.text+`
                    </div>
                    <div class='p-1 pr-2 m-0 card-footer bg-dark text-right'>
                        <a href='#' class='off comment_id text-decoration-none m-0 p-0 text-success' data-id='`+comment.id+`' data-toggle='modal' data-target='#myModal'><strong>response</strong></a>`
                     + config_p + `
                    </div>
                    <div class="ml-3 border-left border-dark comments-parent" data-id="`+comment.id+`"></div>
                </div>`
    if (comment.parent_id === null) {
        $('#comments').append(html);
    }
    else {
        $(('.comments-parent[data-id = "'+comment.parent_id+'"]')).append(html);
    }

}

let parent_id = null;
let edit_id;
let delete_id;

$(document).on("click", ".edit-comment", function () {
    edit_id = $(this).attr('data-id');
    let comment_text = $('.comments-text[data-id = "'+edit_id+'"]').text().trim();
    let html =`<form action="#" id="edit-comment" method="post">
        <textarea required class="form-control border-dark border" maxlength="500" id="modal_comment" name="text" rows="10" cols="70">`+ comment_text +`</textarea>
        <div class="mt-2 float-right">
            <button type="submit"   id="edit_button" class="off btn btn-success" ><strong>Save </strong></button>
            <button type="button" class=" btn btn-danger" data-dismiss="modal"><strong>Close</strong></button>
        </div>
     </form>`
    $(".modal-title").text("Edit comment");
    $(".modal-body").html(html);
});

$(document).on("click", ".delete-comment", function () {
    delete_id = $(this).attr('data-id');
    let html =`
            <button type="button"   id="delete-button" class="m-auto off btn btn-danger " ><strong>Delete</strong></button>
            <button type="button" class="m-auto btn btn-success" data-dismiss="modal"><strong>Close</strong></button>`
    $(".modal-title").text("DELETE");
    $(".modal-body").html(html);
    return false;
});

$(document).on("click", ".comment_id", function () {
    parent_id = $(this).attr('data-id');
    let html =`<form action="#" class="form-comment">
        <textarea required class="form-control border-dark border" maxlength="500" id="modal_comment" name="text" rows="10" cols="70"></textarea>
        <div class="mt-2 float-right">
            <button type="submit"   id="modal_button" class="off btn btn-success" ><strong>Send</strong></button>
            <button type="button" class=" btn btn-danger" data-dismiss="modal"><strong>Close</strong></button>
        </div>
     </form>`
    $(".modal-title").text("Leave a comment");
    $(".modal-body").html(html);
});

$('#myModal').on('hide.bs.modal', function() {
    parent_id = null;
    edit_id = "";
    delete_id = "";
})

$(document).on("submit", ".form-comment", function () {
    $(".off").attr('disabled', true);
    let form_data = new FormData($(this)[0]);
    form_data.append("parent_id", parent_id);

    $(this).find("textarea").val("");
    $.ajax({
        url: "/comments/create",
        type : "POST",
        cache: false,
        contentType: false,
        processData: false,
        data : form_data,
        success : function(result) {
            parent_id = null;
            PrintComment(result.data);
            $(".comment-img").error(function() {
                $(this).attr('src', '/api/image/nan.png?' + Math.random());
            });
            $(".off").attr('disabled', false);
            $('#myModal').modal('hide');
        },
        error : function(result) {
            $(".off").attr('disabled', false);
        }
    })
    return false;
})

$(document).on("click", "#delete-button", function () {
    $(".off").attr('disabled', true);
    let form_data = new FormData();
    form_data.append("id", delete_id);

    $.ajax({
        url: "/comments/delete",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (result) {
            $(".comments[data-id = "+delete_id+"]").remove();
            $('#myModal').modal('hide');
            $(".off").attr('disabled', false);
        },
        error: function (result) {
            $(".off").attr('disabled', false);
        }
    })
    return false;
})

$(document).on("submit", "#edit-comment", function () {
    $(".off").attr('disabled', true);
    let form_data = new FormData($(this)[0]);
    form_data.append("id" , edit_id);
    $(this).find("textarea").val("");
    $.ajax({
        url: "/comments/edit",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function () {
            let text = $("#edit-comment").find('textarea').val();
           // $(".comments[data-id = '"+edit_id+"']").find(".date-comment").find("lable").text("(edited)")
            $(".date-comment[data-id = '"+edit_id+"']").find("lable").text("(edited)");
            $(".comments-text[data-id = '"+edit_id+"']").html(form_data.get("text"));
            $('#myModal').modal('hide');
            $(".off").attr('disabled', false);
        },
        error: function (result) {
            console.log(result);
            $(".off").attr('disabled', false);
        }
    })
    return false;
})


