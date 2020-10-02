<?php require_once "block/header.php" ?>
    <link rel="stylesheet" type="text/css" href="../Style/log_reg.css">
    <main class="p-5 container">
        <h1 class="mb-3 text-center">Create account</h1>
        <div id="form-register" class="mt-5 my-auto mx-auto text-right">
            <form action="register" class="form" method="post">
                <input type="text" class="form-control">
                <input type="text" class="form-control">
                <input type="email" class="form-control">
                <input type="date" class="form-control">
                <input type="text" class="form-control">
                <input type="password" class="form-control">
                <input type="password" class="form-control">
                <button class="btn" type="submit">Submit</button>
            </form>
        </div>
    </main>
<?php require_once "block/footer.php" ?>