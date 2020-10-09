<?php
header("Content-Type: application/json; charset=UTF-8");
require_once ROOT . "/Model/CommentsModal.php";
require_once ROOT . "/Core/SendMail.php";


class CommentsController
{
    function __construct()
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

    public function actionLoad()
    {
        $comment = new CommentsModal();
        $data = $comment->LoadComment();
        $this->PrintMessage("success","Load comments", 200, $data);

    }

    public function actionCreate()
    {   session_start();
        if(!isset($_SESSION["login"])){
            $this->PrintMessage("error","not sign in", 400,null);
        }
        if((empty($_POST["text"]))) {
            $this->PrintMessage("error","empty comment text", 400,null);
        }
        $parent_id = null;
        if(isset($_POST["parent_id"])) {
            $parent_id = $_POST["parent_id"];
            if($parent_id === "null") {
                $parent_id = null;
            }
        }
        $user_id = $_SESSION["login"];
        $text = $_POST["text"];
        $comment = new CommentsModal();
        $date = date("Y-m-d H:i:s");
        $id_comment = $comment->Create($user_id, $text, $date, $parent_id);
        if($id_comment === null) {
            $this->PrintMessage("error","error while creating a comment", 400,$id_comment);
        }

        $send = new SendMail();
        $user_data = $comment->GetUserDate($id_comment);
        $msg = "<b>Date:</b> $date<br><br>
                <b>Text:</b><br>$text";
        $send->sendMassage($user_data["email"], $msg,"Your comment");
        if($parent_id !== null) {
            $parent_data = $comment->GetUserDate($parent_id);
            $msg = "<b>Name:</b>".$user_data["first_name"]." ".$user_data["second_name"]."<br>
                    <b>Email:</b>".$user_data["email"]."<br><br>
                    <b>Date:</b> $date<br><br>
                    <b>Text:</b><br>$text";
            $send->sendMassage($parent_data["email"], $msg,"Your comment was answered");
        }
        $this->PrintMessage("success","comment created", 200, $comment->getComment($id_comment));
    }

    public function actionEdit()
    {
        session_start();
        if(!isset($_SESSION["login"])){
            $this->PrintMessage("error","not sign in", 400,null);
        }
        $user_id = $_SESSION["login"];
        if((empty($_POST["text"]))) {
            $this->PrintMessage("error","empty comment text", 400,null);
        }
        $text = $_POST["text"];
        if((empty($_POST["id"]))) {
            $this->PrintMessage("error","empty comment text", 400,null);
        }
        $id_comment = $_POST["id"];
        $comment = new CommentsModal();

        if($comment->Edit($user_id,$id_comment,$text)) {
            $this->PrintMessage("success","comment edited", 200, null);
        }
        $this->PrintMessage("error","failed to edited comment", 400,null);
    }

    public function actionDelete()
    {
        session_start();
        if(!isset($_SESSION["login"])){
            $this->PrintMessage("error","not sign in", 400,null);
        }
        $user_id = $_SESSION["login"];
        if(!isset($_POST["id"])) {
            $this->PrintMessage("error","no comment for deletion not found", 400,null);
        }
        $comment = new CommentsModal();
        $delete_id = $_POST["id"];
        if( $comment->delete($user_id,$delete_id) ) {
            $this->PrintMessage("success","comment delete", 200, null);
        }
        $this->PrintMessage("error","failed to delete comment", 400,null);
    }

}
