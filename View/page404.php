<?php
    require_once "block/header.php";
    if($res) {
        require_once "block/nav.php";
    }
    ?>
    <main class="p-5 container">
        <div class="p-5 alert-danger mt-5 my-auto mx-auto ">
            <h1 class="mb-3 text-center">Error 404</h1>
            <h2 class="mb-3  text-center">Page not found</h2>
        </div>
    </main>
<?php require_once "block/footer.php" ?>
