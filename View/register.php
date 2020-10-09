<?php require_once "block/header.php" ?>
    <link rel="stylesheet" type="text/css" href="../Style/log_reg.css">
    <main class="p-5 container">
        <h1 class="mb-3 text-center">Create account</h1>
        <div id="form-div" class=" mt-5 my-auto mx-auto ">
            <div class = "status-message  alert d-none text-center p-3  rounded" role="alert"></div>
            <form action="/register" id="register-form" method="post" class="p-0 m-5">
                <div class="row">
                    <input required type="text" class="col form-control form-control-lg" name="first_name" placeholder="First name">
                    <input required type="text" class="col form-control form-control-lg" name="second_name" placeholder="Second name">
                    <input required type="email" class="form-control form-control-lg" name="email" placeholder="Email address">
                </div>
                <div class="row">
                    <input required type="tel" pattern="[0-9]{5,15}" class="col form-control form-control-lg" name="number" placeholder="Number">
                    <input required type="date" class="col form-control form-control-lg" max="" name="date" >
                </div>
                <div class="row">
                    <select required id="country" class="col mr-1 custom-select custom-select-lg"></select>
                    <select required id="region" class="col mr-1 custom-select custom-select-lg"></select>
                    <select required id="city" class="col custom-select custom-select-lg"></select>
                </div>
                <div class="row">
                    <input required type="password" class="col border form-control form-control-lg" name="password" autocomplete="on" placeholder="Password">
                    <input required type="password" class="col border form-control form-control-lg" name="confirm_password"  autocomplete="on" placeholder="Confirm password">
                </div>
                <div class="float-right row">
                    <button type="submit" name="submit" class="btn btn-lg"><strong>Register</strong></button>
                </div>
                <a class="text-center text-decoration-none" href="/sign-in">Sign in</a>
            </form>
        </div>
        <script src="../Script/location.js" type="application/javascript"></script>
        <script src="../Script/register.js" type="application/javascript"></script>
    </main>
<?php require_once "block/footer.php" ?>