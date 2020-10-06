<?php
    return array(
        "create-user" => "User/Register",
        "login-user" => "User/SignIn",
        "update-user" => "User/EditData",
        "update-image" => "User/EditImage",
        "validate" => "User/Validate",
        "sign-out" => "User/SignOut",
        "country" => "Location/Country",
        "region[?id=0-9]{0,}" => "Location/Region",
        "city[?id=0-9]{0,}" => "Location/City",
        "register" => "Site/Register",
        "sign-in" => "Site/SignIn",
        "cabinet" => "Site/Cabinet",
        "test" => "Site/Test",
         "" => "Site/SignIn",
        "[a-zA-Z0-9/]{1,}" => "Site/Error404",
    );