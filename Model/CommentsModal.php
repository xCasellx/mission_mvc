<?php

class CommentsModal
{
    private $db;
    private $table_name = "comments";

    function __construct()
    {
        $this->db = DataBase::getConnection();
    }

    public function getComment($id_comment)
    {
        $query ="SELECT comments.* , user.first_name, user.second_name, user.image FROM ".$this->table_name."
                 JOIN user on user.id = comments.user_id 
                 WHERE comments.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id_comment);
        if($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $res["image"] = "http://".$_SERVER['HTTP_HOST']."/img/".$res["image"]."/user-image.jpg";
            return  $res;
        }
        return null;
    }

    public function LoadComment()
    {
        $query ="SELECT comments.* , user.first_name, user.second_name, user.image FROM ".$this->table_name."
                 JOIN user on user.id = comments.user_id";
        $stmt = $this->db->prepare($query);
        if($stmt->execute()) {
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $arr_comment = array();
            foreach ($res as $key ) {
                $key["image"] = "http://".$_SERVER['HTTP_HOST']."/img/".$key["image"]."/user-image.jpg";
                $arr_comment[] = $key;
            }
            return  $arr_comment;
        }
        return null;
    }
    public function GetUserDate($comment_id)
    {
        $query ="SELECT user.first_name, user.second_name, user.email FROM ".$this->table_name."
                 JOIN user on user.id = comments.user_id
                 WHERE comments.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $comment_id);
        if($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;

    }

    public function Create($user_id, $text, $date, $parent_id)
    {

        $query = "INSERT INTO $this->table_name (user_id, date, text, parent_id) VALUES 
                                            (:user_id, :date, :text, :parent_id)";
        $stmt = $this->db->prepare($query);
        $text = htmlspecialchars(strip_tags($text));
        $stmt->bindParam(":parent_id", $parent_id);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":user_id", $user_id);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return null;
    }

    public function Edit($user_id, $id_comment , $text)
    {
        $query = "UPDATE " . $this->table_name . " SET text = :text , edit_check = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $text = htmlspecialchars(strip_tags($text));
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":id", $id_comment);
        $stmt->bindParam(":user_id", $user_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function delete($user_id, $id_comment)
    {
        $sql = "DELETE FROM ".$this->table_name." WHERE id =  :id_comment and user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_comment", $id_comment);
        $stmt->bindParam(":user_id", $user_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
