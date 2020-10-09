<?php
header("Content-Type: application/json; charset=UTF-8");
require_once ROOT . "/Model/LocationModal.php";
class LocationController
{
    function __construct()
    {
    }
    function actionCountry()
    {
        echo json_encode(LocationModal::getCountry());
    }

    function actionRegion()
    {
        if(isset($_GET["id"])) {
            echo json_encode(LocationModal::getRegion($_GET["id"]));
        }
    }
    function actionCity()
    {
        if(isset($_GET["id"])) {
            echo json_encode(LocationModal::getCity($_GET["id"]));
        }
    }
}