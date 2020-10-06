<?php
class UserModal
{
    private static $data_column = ["first_name", "second_name", "email", "date", "number",
                                    "town" , "password" ];
    private  $data_public = ["first_name", "second_name", "email", "date", "number",
        "city", "region", "country" , "image" ];
    private $table_name = "user";

    function __construct()
    {

    }

    private function GetInParams()
    {
        $str = "";
        foreach ( self::$data_column as $data) {
            $str .="$data = :$data, ";
        }
        return $str = substr($str,0,-2);;
    }

    static function GetDataColumn()
    {
        return self::$data_column;
    }

    function GetUserData($id)
    {
        $db = DataBase::getConnection();
        $query ='SELECT user.*, city.name AS city , region.name as region , country.name as country FROM user 
            JOIN city ON city.id = user.town 
            JOIN region ON region.id = city.region_id 
            JOIN country ON country.id = region.country_id 
            WHERE user.id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $res = [];
            foreach ($this->data_public as $value) {
                $res[$value] = $row[$value];
            }
            return $res;
        }
        return false;
    }

    function CheckEmail($email)
    {
        $db = DataBase::getConnection();
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }


    function SignIn($email , $password)
    {
        $db = DataBase::getConnection();
        $query = "SELECT id , email , password  FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $db->prepare($query);
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

    function createAccount($user_data)
    {
        $db = DataBase::getConnection();
        $in = $this->GetInParams();
        $query = "INSERT INTO " . $this->table_name . " SET ".$in;
        $stmt = $db->prepare($query);
        if (!$stmt) {
            return $db->errorInfo();
        }
        $res = array();
        foreach (self::$data_column as $col) {
            $stmt->bindParam(":".$col, $user_data[$col]);
        }
        if ($stmt->execute()) {
            return "success";
        }
        return $db->errorInfo();
    }
}