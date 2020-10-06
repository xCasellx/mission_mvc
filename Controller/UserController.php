<?php
header("Content-Type: application/json; charset=UTF-8");
require_once ROOT . "/Model/UserModal.php";

class UserController
{
    private $pattern_password = '/(?=.*[0-9])(?=.*[!@#$%^&*_])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*_]{6,}/';

    function  __construct()
    {
    }

    private function  PrintMessage($status, $message, $code , $data)
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

    private  function  checkColumn($text)
    {
        $col = UserModal::GetDataColumn();
        foreach ($col as $item) {
            if($item === $text ) {
                return true;
            }
        }
        return false;
    }

    function actionEditImage()
    {
        session_start();
        if(isset($_SESSION["login"])) {
            $image = array();
            if(isset($_FILES["image"])) {
                $image = $_FILES["image"];
            }
            else {
                $this->PrintMessage("error","Image empty", 400 , null);
            }
            $edit_id = $_SESSION["login"];
            $fold_name = "user-".$edit_id;
            $full_path =ROOT."/user-data/".$fold_name;
            if ( !file_exists($full_path) ) {
                $oldumask = umask(0);
                mkdir($full_path, 0777, true);
                umask($oldumask);
            }
            $tmp_name = $image["tmp_name"];
            $full_path_image = $full_path . "/user-image.jpg";
            move_uploaded_file($tmp_name, $full_path_image);
            $user = new UserModal();
            if($user->updateData("image", $fold_name, $edit_id)) {
                $data = $user->GetUserData($edit_id);
                $this->PrintMessage("success","Update success", 200 , $data);
            }
            else {
                $this->PrintMessage("error","Error upd", 400 , null);
            }
        }
        else {
            $this->PrintMessage("error","Error ", 400 , null);
        }

    }

    function actionEditData()
    {
        session_start();
        if($_SESSION["login"]) {
            $edit_id = $_SESSION["login"];
            $user = new UserModal();
            if(!empty($_POST)){
                $edit_name = $_POST["edit_name"];
                $edit_text = $_POST["edit_text"];
                if(!$this->checkColumn($edit_name)) {
                    $this->PrintMessage("error","This field does not exist", 400 , null);
                }
                $error = "";
                switch ($edit_name) {
                    case "password":
                        $password = $_POST["password"];
                        $confirm_password = $_POST["confirm_password"];
                        if (empty($password) && empty($confirm_password)) {
                            $this->PrintMessage("error","Empty password", 400 , null);
                        }
                        if (!preg_match($this->pattern_password, $edit_text)) {
                            $this->PrintMessage("error","The password must be at least 6 or more.
                              Password must consist of letters of the Latin alphabet (A-z),
                              numbers (0-9) and special characters.", 400 , null);

                        }
                        if (!$user->passwordVerify($password,$edit_id)) {
                            $this->PrintMessage("error","Incorrect passwords.", 400 , null);
                        }
                        if ($edit_text !== $confirm_password) {
                            $this->PrintMessage("error","Password mismatch.", 400 , null);
                        }
                        $edit_text = password_hash($edit_text, PASSWORD_BCRYPT);
                        break;
                    case "date":
                        $temp_date = strtotime($edit_text);
                        $temp_upd = strtotime((date('Y')-5).date('-m-d'));
                        if ($temp_date > $temp_upd) {
                            $this->PrintMessage("error","Wrong date.", 400 , null);
                        }
                        break;
                    default:
                        $edit_text = htmlspecialchars(strip_tags($edit_text));
                        break;
                }
                if($user->updateData($edit_name, $edit_text, $edit_id)) {
                    $data = $user->GetUserData($edit_id);
                    $this->PrintMessage("success","Update success", 200 , $data);
                }
            }
        }
        $this->PrintMessage("error","edit error", 400 , null);
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

    function  actionSignOut()
    {
        session_start();
        unset($_SESSION["login"]);
        $this->PrintMessage("success","LogOut", 200 ,null);
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
}
