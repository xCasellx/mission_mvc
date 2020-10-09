<?php
class UserModal
{
    private static $data_column = ["first_name", "second_name", "email", "date", "number",
                                    "town" , "password" ];
    private  $data_public = ["id", "first_name", "second_name", "email", "date", "number",
        "city", "region", "country" , "image", "email_activate" ];
    private $table_name = "user";
    private $db;

    function __construct()
    {
        $this->db = DataBase::getConnection();
    }

    private function GetInParams($params)
    {
        $str = "";
        foreach ( $params as $key => $val) {
            $str .="$key = :$key, ";
        }
        return $str = substr($str,0,-2);;
    }

    static function GetDataColumn()
    {
        return self::$data_column;
    }

    public function GetUserData($id)
    {
        $query ='SELECT user.*, city.name AS city , region.name as region , country.name as country FROM user 
            JOIN city ON city.id = user.town 
            JOIN region ON region.id = city.region_id 
            JOIN country ON country.id = region.country_id 
            WHERE user.id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $res = [];
            foreach ($this->data_public as $value) {
                $res[$value] = $row[$value];
            }
            $res["image"] = "http://".$_SERVER['HTTP_HOST']."/img/".$res["image"]."/user-image.jpg";
            return $res;
        }
        return false;
    }

    public function GetFullUserData($id)
    {
        $query ='SELECT user.*, city.name AS city , region.name as region , country.name as country FROM user 
            JOIN city ON city.id = user.town 
            JOIN region ON region.id = city.region_id 
            JOIN country ON country.id = region.country_id 
            WHERE user.id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()){
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }
        return false;
    }

    public function update($data, $where)
    {
        $query = "UPDATE " . $this->table_name . " SET " .$data. " WHERE ".$where;
        $stmt = $this->db->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;

    }

    public function CheckHash($hash)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE hash = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $hash);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function CheckEmail($email)
    {

        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function  passwordVerify($password, $id)
    {

        $query = "SELECT password  FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0) {
            return false;
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!password_verify($password , $user["password"])) {
            return false;
        }
        return  true;
    }

    public function SignIn($email , $password)
    {
        $query = "SELECT id , email , password  FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        if ($stmt->rowCount() <= 0) {
            return false;
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!password_verify($password , $user["password"])) {
            return false;
        }
        return $user["id"];
    }

    public function createAccount($user_data)
    {
        $in = $this->GetInParams($user_data);
        $query = "INSERT INTO " . $this->table_name . " SET ".$in;
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return $this->db->errorInfo();
        }
        foreach (self::$data_column as $col) {
            $stmt->bindParam(":".$col, $user_data[$col]);
        }
        if ($stmt->execute()) {
            return "success";
        }
        return $this->db->errorInfo();
    }
}