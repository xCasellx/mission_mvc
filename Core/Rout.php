<?php
class Rout
{
    private $router;

    function __construct()
    {
        $this->router = include ROOT."/Config/router.php";
    }
    private function getURL()
    {
        if(!empty($_SERVER["REQUEST_URI"])) {
            return trim($_SERVER["REQUEST_URI"],"/");
        }
    }

    function run()
    {
        $url = $this->getURL();
        foreach ($this->router as $pattern => $path) {
            $pattern = "^$pattern$";
            if(preg_match("~$pattern~", $url)){
                $segment = explode("/", $path);
                $className =array_shift( $segment)."Controller";
                $actionName = "action".array_shift($segment);
                $pathClass = ROOT."\Controller\\$className.php";

                if(file_exists($pathClass)) {
                    include_once ($pathClass);
                }
                $controller = new $className;
                if(method_exists($controller, $actionName)) {
                    $controller->$actionName();
                    break;
                }
            }
        }
    }
}