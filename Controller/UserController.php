<?php
header("Content-Type: application/json; charset=UTF-8");
require_once ROOT . "/Model/UserModal.php";
require_once ROOT . "/core/SendMail.php";

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

    public function actionEditImage()
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

    public function actionEmailVerify()
    {
        session_start();
        if(isset($_SESSION["login"])) {
            if(isset($_GET["hash"])) {
                $hash = $_GET["hash"];
                if(!preg_match("~activateEmail$~",$hash )) {
                    $this->PrintMessage("error","Invalid hash ", 400 , null);
                }
                $user = new UserModal();
                if(!$user->CheckHash($hash)) {
                    $this->PrintMessage("error","Invalid hash ", 400 , null);
                }
                $data = "email_activate = 1 , hash = null";
                $user->update($data, "hash = '$hash'");
                $this->PrintMessage("success","Email confirm" , 200, null);

            }
        }
    }

    public function actionEmailSendVerify()
    {
        session_start();
        if(isset($_SESSION["login"])) {
            $user_id = $_SESSION["login"];
            $user = new UserModal();
            $user_data = $user->GetUserData($user_id);
            if($user_data["email_activate"] === 1) {
                $this->PrintMessage("success","Email activated. ", 200 , null);
            }
            $mail_send = new SendMail();
            $hash = $mail_send->sendEmailHash($user_data["email"],"Follow the link to verify your email"
                                            ,"activateEmail", "email/verify","Email verify");
            if($user->update("hash = '$hash'","id = ".$user_id)){
                $this->PrintMessage("success","The letter was sent to the mail", 200 , null);
            }
            $this->PrintMessage("error","sending error", 400 , null);
        }
    }

    public function actionPasswordSendRecovery()
    {
        if(isset($_POST["email"])) {
            $user_email = $_POST["email"];
            $user = new UserModal();
            if(!$user->CheckEmail($user_email)){
                $this->PrintMessage("success","The letter was sent to the mail", 200 , null);
            }
            $mail_send = new SendMail();
            $hash = $mail_send->sendEmailHash($user_email,"Follow the link to recover your password"
                ,"recoveryPassword", "recovery/password","Recovery password");
            if($user->update("hash = '$hash'","email = '$user_email'")){
                $this->PrintMessage("success","The letter was sent to the mail", 200 , null);
            }
        }
        $this->PrintMessage("error","sending error", 400 , null);
    }

    public function actionPasswordRecovery()
    {
        if(isset($_POST["hash"])) {
            $hash = $_POST["hash"];
            if(!preg_match("~recoveryPassword$~",$hash )) {
                $this->PrintMessage("error","Invalid hash ", 400 , null);
            }
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];
            if (empty($password) || empty($confirm_password)) {
                $this->PrintMessage("error","Empty password", 400 , null);
            }
            if (!preg_match($this->pattern_password, $password)) {
                $this->PrintMessage("error","The password must be at least 6 or more.
                              Password must consist of letters of the Latin alphabet (A-z),
                              numbers (0-9) and special characters.", 400 , null);

            }
            if ($password !== $confirm_password) {
                $this->PrintMessage("error","Password mismatch.", 400 , null);
            }
            $user = new UserModal();
            if(!$user->CheckHash($hash)) {
                $this->PrintMessage("error","Invalid hash ", 400 , null);
            }
            $password = password_hash($password, PASSWORD_BCRYPT);
            $data = "password = '$password', hash = null";
            if(!$user->update($data, "hash = '$hash'")) {
                $this->PrintMessage("error","Not a known mistake in password recovery" , 400, null);
            }
            $this->PrintMessage("success","Password recovery" , 200, null);
        }

    }

    public function actionEmailUpdate()
    {
        session_start();
        if(isset($_SESSION["login"])) {
            if(isset($_GET["hash"])) {
                $hash = $_GET["hash"];
                $user_id = $_SESSION["login"];
                if(!preg_match("~editEmail$~",$hash )) {
                    $this->PrintMessage("error","Invalid hash ", 400 , null);
                }
                $user = new UserModal();
                $upd_email = $user->GetFullUserData($user_id)["upd_email"];
                if(!$user->CheckHash($hash)) {
                    $this->PrintMessage("error","Invalid hash ", 400 , null);
                }
                $data = "email = '$upd_email', upd_email = null , hash = null";
                $user->update($data, "hash = '$hash'");
                $this->PrintMessage("success","Email update" , 200, null);
            }
        }
    }

    public function actionEditData()
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
                switch ($edit_name) {
                    #not break;
                    case  "email":
                        $password = $_POST["password"];
                        $email = $_POST["edit_text"];
                        if (empty($password)) {
                            $this->PrintMessage("error","Empty password", 400 , null);
                        }
                        if (!$user->passwordVerify($password,$edit_id)) {
                            $this->PrintMessage("error","Incorrect passwords.", 400 , null);
                        }
                        if($user->CheckEmail($email)) {
                            $this->PrintMessage("error","This email already exists.",400, null);
                        }
                        $mail_send = new SendMail();
                        $hash = $mail_send->sendEmailHash($email,"Follow the link to edit your email"
                            ,"editEmail", "email/update","Email edit");
                        $user->update("hash = '$hash', upd_email = '$email'","id = ".$edit_id);
                        $data = $user->GetUserData($edit_id);
                        $this->PrintMessage("success","The letter was sent to the mail", 200 , $data);
                    case "password":
                        $password = $_POST["password"];
                        $confirm_password = $_POST["confirm_password"];
                        if (empty($password) || empty($confirm_password)) {
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
                if($user->update("$edit_name = '$edit_text'", "id = ". $edit_id)) {
                    $data = $user->GetUserData($edit_id);
                    $this->PrintMessage("success","Update success", 200 , $data);
                }
            }
        }
        $this->PrintMessage("error","edit error", 400 , null);
    }

    public function actionSignIn()
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

    public function  actionSignOut()
    {
        session_start();
        unset($_SESSION["login"]);
        header("Location:/sign-in");
    }

    public function actionValidate()
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

    public function actionRegister()
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
                    $this->PrintMessage("error","Fill in all the input fields",400, null);
                }
            }
            if($user->CheckEmail($user_data["email"])) {
                $this->PrintMessage("error","This email already exists.",400, null);
            }
            $temp_date = strtotime($user_data["date"]);
            $temp_upd = strtotime((date('Y')-5).date('-m-d'));
            if ($temp_date > $temp_upd) {
                $this->PrintMessage("error","Wrong date.",400,null);
            }
            if(!preg_match($this->pattern_password, $user_data["password"])) {
                $this->PrintMessage("error","The password must be at least 6 or more.
                              Password must consist of letters of the Latin alphabet (A-z),
                              numbers (0-9) and special characters.",400, null);
            }
            if($user_data["password"] !== $_POST["confirm_password"]) {
                $this->PrintMessage("error","Passwords do not match",400,null);
            }

            $user_data["password"] = password_hash($user_data["password"], PASSWORD_BCRYPT);

            $res = $user->createAccount($user_data);

            if( $res === "success") {
                $this->PrintMessage("success","Create success.",201, null);
            }
            else {
                $this->PrintMessage("error","Create error ",400, $res);
            }

        }
    }
}
