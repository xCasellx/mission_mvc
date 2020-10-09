<?php
    return array(
        #Location control
        "country" => "Location/Country",
        "region[?id=0-9]{0,}" => "Location/Region",
        "city[?id=0-9]{0,}" => "Location/City",

        #User Control
        "create-user" => "User/Register",
        "login-user" => "User/SignIn",
        "update-user" => "User/EditData",
        "update-image" => "User/EditImage",
        "validate" => "User/Validate",
        "sign-out" => "User/SignOut",
        "email/send/verify" => "User/EmailSendVerify",
        "email/user/verify?.*" => "User/EmailVerify",
        "email/user/update?.*" => "User/EmailUpdate",
        "password/recovery/send" => "User/PasswordSendRecovery",
        "password/recovery" => "User/PasswordRecovery",

        #Comment Control
        "comments/load" => "Comments/Load",
        "comments/create" => "Comments/Create",
        "comments/delete" => "Comments/Delete",
        "comments/edit" => "Comments/Edit",

        #Site Control
        "email/verify.*" => "Site/EmailVerify",
        "email/update.*" => "Site/EmailUpdate",
        "recovery/password.*" => "Site/PasswordRecovery",
        "recovery" => "Site/Recovery",
        "register" => "Site/Register",
        "sign-in" => "Site/SignIn",
        "cabinet" => "Site/Cabinet",
        "comment" => "Site/Comments",
        "test" => "Site/Test",
         "" => "Site/SignIn",
        ".{1,}" => "Site/Error404",
    );