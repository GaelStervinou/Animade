<?php
namespace App;

use App\Core\Security;

session_start();

require "conf.inc.php";


function myAutoloader($class){
    //$class = App\Core\CleanWords
    $class = str_ireplace("App\\", "", $class);
    //$class = Core\CleanWords
    $class = str_ireplace("\\", "/", $class);
    //$class = Core/CleanWords
    if(file_exists($class.".class.php")){
        include $class.".class.php";
    }
}

spl_autoload_register("App\myAutoloader");


$uri = $_SERVER["REQUEST_URI"];
$parameters_pos = strpos($uri, "?");
if($parameters_pos != false){
    $uri = substr($uri, 0, $parameters_pos);
}

$routeFile = "routes.yml";
if(!file_exists($routeFile)){
    die("Le fichier ".$routeFile." n'existe pas");
}
$routes = yaml_parse_file($routeFile);

if( empty($routes[$uri]) || empty($routes[$uri]["controller"])  || empty($routes[$uri]["action"]) ){
        die("Page 404");
}
if(!empty($routes[$uri]["security"])){
    $security = $routes[$uri]["security"]['rule'];
    if(is_array($security)){
        if(isset($security['delete'])){
            $response = Security::canDelete($security['delete']);
        }elseif(isset($security['update'])){
            $response = Security::canUpdate($security['update']);
        }else{
            $response = false;
        }
    }else{
        $response = match ($security) {
            'user' => Security::isUser(),
            'author' => Security::isAuthor(),
            'admin' => Security::isAdmin(),
            ['delete'] => Security::canDelete($security['delete']),
            ['update'] => Security::canUpdate($security['update']),
            default => Security::isConnected(),
        };
    }
    if($response !== true){
        return $response;
    }
}

$controller = ucfirst(strtolower($routes[$uri]["controller"]));
$action = strtolower($routes[$uri]["action"]);


// $controller = User ou $controller = Global
// $action = login ou $action = logout ou $action = home

$controllerFile = "Controller/".$controller.".class.php";
if(!file_exists($controllerFile)){
    die("Le controller ".$controllerFile." n'existe pas");
}
include $controllerFile;

$controller = "App\\Controller\\".$controller;

if( !class_exists($controller) ){
   die("La classe ".$controller." n'existe pas");
}

$objectController = new $controller();

if( !method_exists($objectController, $action) ){
    die("La methode ".$action." n'existe pas");
}

$objectController->$action();




