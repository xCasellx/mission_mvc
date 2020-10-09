<?php require_once "block/header.php" ?>
<link rel="stylesheet" type="text/css" href="../Style/log_reg.css">
<main class="p-5 container">
    <h1 class="mb-3 text-center">Sign In</h1>
    <div id="form-div" class=" mt-5 my-auto mx-auto ">
        <div class = "status-message  alert d-none text-center p-3 h5 rounded" role="alert"></div>
        <form action="/recovery" id="recovery-form" method="post" class="p-0 m-5">
            <input required type="email" class="form-control form-control-lg" name="email" placeholder="Email address">
            <button type="submit" name="submit" class="float-right btn btn-lg"><strong>Recovery</strong></button>
            <a class="text-center text-decoration-none" href="/sign-in">Sign in</a>
        </form>
    </div>

    <script src="../Script/recovery.js" type="application/javascript"></script>
</main>
<?php require_once "block/footer.php" ?>
