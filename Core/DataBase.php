<?php
class DataBase{
    private static $host = "localhost";
    private static $db_name = "users";
    private static $username = "root";
    private static $password = "root";

    public static function  getConnection()
    {
        $conn=null;
        try{
            $conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
            $conn->exec("set names utf8");
        }
        catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $conn;
    }
}