<?php

 ini_set("display_errors",1);
 error_reporting(E_ALL);

 define("ROOT",dirname(__FILE__));
 require_once ROOT."/Core/DataBase.php";
 require_once ROOT."/Core/Rout.php";


 $rout = new Rout();
 $rout->run();