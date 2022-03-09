<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Model\User as UserModel;

class User{

    public function login()
    {
        $user = new UserModel();


        if(!empty($_POST["password"]) && !empty($_POST["email"])){
            $check_password = $user->login($_POST["email"], $_POST["password"]);
            if($check_password == true){
                echo " vous êtes connectés";
                $view = new View("dashboard");
                $view->assign("firstname", $user->getFirstname());
                $view->assign("lastname", $user->getLastname());
            }else{
                echo " mot de passe incorrect";
            }
        }else{
            $view = new View("Login");
            $view->assign("titleSeo","Se connecter au site");
            $view->assign("user", $user);
        }
    }

    public function logout()
    {
        echo "Se deco";
    }

    public function register()
    {
        $user = new UserModel();
        if(!empty($_POST)){
            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }
            $result = Validator::run($user->getFormRegister(), $_POST);
            if(empty($result)){
                $user->setEmail($_POST["email"]);
                $user->setFirstname($_POST["firstname"]);
                $user->setLastname($_POST["lastname"]);
                $user->setPassword($_POST["password"]);
                $user->save();
                $view = new View("dashboard");
                $view->assign("firstname", $user->getFirstname());
                $view->assign("lastname", $user->getLastname());
            }else{
                echo "Formulaire invalide :<br>";
                foreach($result as $error){
                    echo $error ."<br>";
                }
                $user = new UserModel();
                $view = new View("register");
            }
        }else{
            $user = new UserModel();
            $view = new View("register");
        }
        $view->assign('user', $user);
    }

}