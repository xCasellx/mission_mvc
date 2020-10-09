<?php

class LocationModal
{

    static function getCountry()
    {
        $db =DataBase::getConnection();
        $query = "SELECT * FROM country";
        $stmt = $db->prepare($query);;
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }

    static function getRegion($country_id)
    {
        $db = DataBase::getConnection();
        $query = "SELECT * FROM region WHERE country_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $country_id);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }
    static function getCity($region_id)
    {
        $db =DataBase::getConnection();
        $query = "SELECT * FROM city WHERE region_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $region_id);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }
}