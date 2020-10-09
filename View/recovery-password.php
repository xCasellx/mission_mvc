<?php require_once "block/header.php" ?>
<link rel="stylesheet" type="text/css" href="../Style/log_reg.css">
    <main class="p-5 h4 container mx-auto">
        <h1 class = "text-center ">Recovery Password</h1>
        <div id="form-div" class=" mt-5 my-auto mx-auto ">
            <div class = "status-message  alert d-none text-center p-3  rounded" role="alert"></div>
            <form method="post" id="form-recovery" class="p-0 m-5">
                <input required type="password" class="col border form-control form-control-lg" name="password" autocomplete="on" placeholder="New password">
                <input required type="password" class="col border form-control form-control-lg" name="confirm_password"  autocomplete="on" placeholder="Confirm password">
                <button type="submit" name="submit" class="float-right btn btn-lg"><strong>Save</strong></button>
            </form>
        </div>
    </main>
    <script src="../Script/password_recovery.js" type="application/javascript"></script>
<?php require_once "block/footer.php" ?>

