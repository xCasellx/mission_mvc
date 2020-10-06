<?php
    return array(
        "create-user" => "User/Register",
        "login-user" => "User/SignIn",
        "validate" => "User/Validate",
        "sign-out" => "User/SignOut",
        "country" => "Location/Country",
        "region" => "Location/Region",
        "city" => "Location/City",
        "register" => "Site/Register",
        "sign-in" => "Site/SignIn",
        "cabinet" => "Site/Cabinet",
        "test" => "Site/Test",
         "" => "Site/SignIn",
        "[a-zA-Z0-9/]{1,}" => "Site/Error404",
    );