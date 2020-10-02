<?php
class UserModal
{
    private static $data_column = ["first_name", "second_name", "email", "date", "number", "town"];

    function __construct()
    {

    }

    static function GetDataColumn()
    {
        return self::$data_column;
    }

    function createAccount($user_data)
    {
        $db = DataBase::getConnection();
    }
}