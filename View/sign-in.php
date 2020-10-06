<?php require_once "block/header.php" ?>
    <link rel="stylesheet" type="text/css" href="../Style/log_reg.css">
    <main class="p-5 container">
        <h1 class="mb-3 text-center">Sign In</h1>
        <div id="form-div" class=" mt-5 my-auto mx-auto ">
            <div class = "status-message  alert d-none text-center p-3 h5 rounded" role="alert"></div>
            <form action="/sign-in" id="login-form" method="post" class="p-0 m-5">
                <input required type="email" class="form-control form-control-lg" name="email" placeholder="Email address">
                <input required type="password" class="border form-control form-control-lg" name="password" autocomplete="on" placeholder="Password">
                <button type="submit" name="submit" class="float-right btn btn-lg"><strong>Sign In</strong></button>
                <a class="text-center text-decoration-none" href="/register">Create account</a>
            </form>
        </div>
        <script src="../Script/sing-in.js" type="application/javascript"></script>
    </main>
<?php require_once "block/footer.php" ?>