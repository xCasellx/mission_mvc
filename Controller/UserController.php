<?php
header("Content-Type: application/json; charset=UTF-8");
require_once ROOT . "/Model/UserModal.php";

class UserController
{
    private $pattern_password = '/(?=.*[0-9])(?=.*[!@#$%^&*_])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*_]{6,}/';

    function  __construct()
    {
    }

    private function  PrintMessage($status,$message, $code , $data)
    {
        http_response_code($code);
        $smg = array(
            "status" => $status,
            "message" => $message
        );
        if($data  !== null) {
            $smg["data"] = $data;
        }
        echo json_encode($smg);
        exit();
    }

    function actionSignIn()
    {
        if(!empty($_POST)) {
            if((isset($_POST["email"])) && (isset($_POST["password"])) ) {
                $email = $_POST["email"];
                $password = $_POST["password"];
                $user = new UserModal();
                $res = $user->SignIn($email, $password);
                if($res === false) {
                    $this->PrintMessage("error","Incorrect email or passwords.", 400 , null);
                }
                session_start();
                $_SESSION["login"] = $res;
                $this->PrintMessage("success","Login success", 200 , null);
            }
            else {
                $this->PrintMessage("error","Text empty", 400 , null);
            }

        }
    }

    function actionAuthentication()
    {
        session_start();
        if(isset($_SESSION["login"])) {
            $this->PrintMessage("success","Login", 200 ,null);
        }
        $this->PrintMessage("error","Logout", 400 , null);
    }

    function actionValidate()
    {
        session_start();
        if(isset($_SESSION["login"])) {
            $user_id = $_SESSION["login"];
            $user = new UserModal();
            $user_data = $user->GetUserData($user_id);
            $this->PrintMessage("success","Login success", 200 , $user_data);
        }
        $this->PrintMessage("error","Logout", 400 , null);
    }

    function actionRegister()
    {
        $user = new UserModal();
        if(!empty($_POST)) {
            $user_data = [];
            $data_column = UserModal::GetDataColumn();
            foreach ($data_column as $column) {
                if(!empty($_POST[$column])) {
                    if($column !== "password") {
                        $user_data[$column] = htmlspecialchars( strip_tags($_POST[$column]) );
                    }
                    else $user_data[$column] = $_POST[$column];
                }
                else {
                    self::PrintMessage("error","Fill in all the input fields",400, null);
                }
            }
            if($user->CheckEmail($user_data["email"])) {
                self::PrintMessage("error","This email already exists.",400, null);
            }
            $temp_date = strtotime($user_data["date"]);
            $temp_upd = strtotime((date('Y')-5).date('-m-d'));
            if ($temp_date > $temp_upd) {
                self::PrintMessage("error","Wrong date.",400,null);
            }
            if(!preg_match($this->pattern_password, $user_data["password"])) {
                self::PrintMessage("error","The password must be at least 6 or more.
                              Password must consist of letters of the Latin alphabet (A-z),
                              numbers (0-9) and special characters.",400, null);
            }
            if($user_data["password"] !== $_POST["confirm_password"]) {
                self::PrintMessage("error","Passwords do not match",400,null);
            }

            $user_data["password"] = password_hash($user_data["password"], PASSWORD_BCRYPT);

            $res = $user->createAccount($user_data);

            if( $res === "success") {
                self::PrintMessage("success","Create success.",201, null);
            }
            else {
                self::PrintMessage("error","Create error ",400, $res);
            }

        }
    }

    function actionCabinet()
    {
        echo "Controller User - Cabinet ";
    }
}
