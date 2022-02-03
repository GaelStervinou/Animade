<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Model\User as UserModel;

class User{

    public function login()
    {
        $view = new View("Login");
        $view->assign("titleSeo","Se connecter au site");
    }

    public function logout()
    {
        echo "Se deco";
    }

    public function register()
    {
        $user = new UserModel();
        $user = $user->setId(1);
        $token = $user->checkToken($user->getToken());
        if($token == true){
            echo " Vous êtes bien connectés";
        } else {
            echo " Vous n'êtes pas connectés";
        }
        if(!empty($_POST)){
            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }
            $result = Validator::run($user->getFormRegister(), $_POST);
            if(empty($result)){
                echo "Formulaire validé";
            }else{
                echo "Formulaire invalide :<br>";
                foreach($result as $error){
                    echo $error ."<br>";
                }
            }
        }else{
            echo "Pas OK";
        }


        //$user = $user->setId(5);

        $view = new View("register");
        $view->assign('user', $user);
    }

}