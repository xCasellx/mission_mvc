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
    function actionTest(){
        echo $_SERVER['HTTP_HOST'];
    }

    function  actionError404()
    {
        require_once ROOT . "/View/page404.php";
    }

    function actionRegister()
    {
        if($this->checkLogin()) {
            header("Location: /cabinet");
        }
        require_once ROOT . "/View/register.php";
    }
    function actionCabinet()
    {
        if(!$this->checkLogin()) {
            header("Location: /sign-in");
        }
        require_once ROOT . "/View/cabinet.php";

    }
    function actionSignIn()
    {
        if($this->checkLogin()) {
            header("Location: /cabinet");
        }
        require_once ROOT . "/View/sign-in.php";
    }
    function actionComments()
    {
        if(!$this->checkLogin()) {
            header("Location: /sign-in");
        }
        require_once ROOT . "/View/comments.php";
    }
}
