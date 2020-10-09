$(document).ready(function () {
    loadListCountry();
})

$(document).on("change","#country", function () {
    $("#city").empty();
    loadListRegion();
});

$(document).on("change","#region", function () {
    loadListCity();
});

function  loadListCountry() {
    $.ajax({
        url: "/country",
        type : "GET",
        contentType : 'application/json',
        success : function(result) {
            $("#country").html(`<option></option>`);
            result.forEach(element => {
                $("#country").append(`<option data-id = `+element.id+` >`+element.name+`</option>`);
            });
        }
    })
}

function  loadListRegion() {
    let id =$('#country option:selected').attr("data-id");
    $.ajax({
        url: "/region",
        type : "GET",
        data: {id : id},
        contentType: 'application/json',
        success: function(result) {
            $("#region").html("<option></option>");
            result.forEach(element => {
                $("#region").append(`<option data-id = `+element.id+` >`+element.name+`</option>`);
            });
        },
        error: function (result) {
            console.log(result)
        }
    })
}

function  loadListCity() {
    let id =$('#region option:selected').attr("data-id");
    $.ajax({
        url: "/city",
        type : "GET",
        data: {id : id},
        contentType: 'application/json',
        success: function(result){
            $("#city").html(`<option></option>`);
            result.forEach(element => {
                $("#city").append(`<option  data-id = `+element.id+` >`+element.name+`</option>`);
            });
        },
        error: function (result) {
            console.log(result)
        }
    })
}