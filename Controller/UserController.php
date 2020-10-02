<?php
require_once ROOT . "/Model/User.php";
class UserController
{
    function  __construct()
    {
    }
    function actionSingIn()
    {
        echo "Controller User - Sign in ";
    }
    function actionRegister()
    {
        if(!empty($_POST)) {
            $user_data = [];
            $data_column = UserModal::GetDataColumn();
            foreach ($data_column as $column) {
                if(empty($_POST[$column])) {
                    $user_data[$column] = $_POST[$column];
                }
            }
            $user = new UserModal();
            $user->createAccount($user_data);
        }
        require_once ROOT . "/View/register.php";
    }
    function actionCabinet()
    {
        echo "Controller User - Cabinet ";
    }
}