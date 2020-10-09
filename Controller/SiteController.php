<?php

class SiteController
{
    function  __construct()
    {
    }

    private function checkLogin()
    {
        session_start();
        if(isset($_SESSION["login"])){
            return true;
        }
        return false;
    }
    private  function  locationSingIn()
    {
        if(!$this->checkLogin()) {
            header("Location: /sign-in");
        }
    }
    private  function  locationCabinet()
    {
        if($this->checkLogin()) {
            header("Location: /cabinet");
        }
    }
    function actionTest(){
        echo $_SERVER['HTTP_HOST'];
    }

    function  actionEmailVerify()
    {
        $this->locationSingIn();
        $script = "email_confirm.js";
        require_once ROOT . "/View/status.php";
    }
    function  actionEmailUpdate()
    {
        $this->locationSingIn();
        $script = "email_update.js";
        require_once ROOT . "/View/status.php";
    }
    function  actionPasswordRecovery()
    {
        $this->locationCabinet();
        $script = "password_recovery.js";
        require_once ROOT . "/View/recovery-password.php";
    }
    function  actionRecovery()
    {
        $this->locationCabinet();
        require_once ROOT . "/View/recovery.php";
    }
    function  actionError404()
    {
        $res = $this->checkLogin();
        require_once ROOT . "/View/page404.php";
    }

    function actionRegister()
    {
        $this->locationCabinet();
        require_once ROOT . "/View/register.php";
    }
    function actionCabinet()
    {
        $this->locationSingIn();
        require_once ROOT . "/View/cabinet.php";

    }
    function actionSignIn()
    {
        $this->locationCabinet();
        require_once ROOT . "/View/sign-in.php";
    }
    function actionComments()
    {
        $this->locationSingIn();
        require_once ROOT . "/View/comments.php";
    }
}
