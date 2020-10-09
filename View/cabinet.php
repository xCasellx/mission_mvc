<?php require_once "block/header.php" ?>
<?php require_once "block/nav.php" ?>
    <main class="p-2 container mx-auto">
        <div class="p-2 status-message"> </div>
        <div class="card mt-2 p-0 container ">
            <div class="text-center text-white card-header"><h3>User information</h3></div>
            <div class="row card-body ">
                <div class="col" style="">
                    <img class="rounded img mx-auto d-block" id="user-image" src="../image/nan.png"  alt="" style="max-width: 100%">
                </div>
                <div class="col border-left border-dark">
                    <div class="col border-left border-dark">
                        <strong>Name: </strong><label id="user_first_name"></label>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-first_name">edit</a><br>
                        <strong>Surname: </strong><label id="user_second_name"></label>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-second_name">edit</a><br>
                        <strong>Number: </strong><label id="user_number"></label>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-number">edit</a><br>
                        <strong>Date: </strong><label id="user_date"></label>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-date">edit</a><br>
                        <strong>Town: </strong><label id="user_town"></label>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-town">edit</a><br>
                        <strong>Email address: </strong><label id="user_email"></label>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-email">edit</a><br>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="edit-data text-decoration-none d-none float-right text-success" id="edit-password">edit password</a><br>
                        <a href="#" data-toggle='modal' data-target='#myModal' class="text-decoration-none d-none float-right text-success" id="edit-image">edit image</a><br>
                        <div class="mt-3 bg-dark rounded p-1" ></div>
                        <a href="#" id="open-edit-data" class="text-decoration-none float-right text-success mr-2 ">Edit</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal">
            <div class=" modal-dialog" >
                <div class="bg-dark modal-content p-0" >
                    <div class="text-center text-light modal-header">
                        <div class="container">
                            <h4 class="modal-title">Edit</h4>
                        </div>
                    </div>
                    <div class="bg-light modal-body" >

                    </div>
                    <div class="p-1 bg-dark modal-footer"></div>
                </div>
            </div>
        </div>
    </main>
    <script src="../Script/cabinet.js" type="application/javascript"></script>
    <script src="../Script/location.js" type="application/javascript"></script>
<?php require_once "block/footer.php" ?>
