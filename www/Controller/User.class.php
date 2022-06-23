<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Core\PHPMailer;
use App\Core\SMTP;
use App\Core\Exception;
use App\Core\Security;

use App\Model\User as UserModel;

class User{

    public function login()
    {
        $user = new UserModel();

        if(!empty($_POST["password"]) && !empty($_POST["email"])){
            $check_password = $user->login($_POST["email"], $_POST["password"]);
            if($check_password == true){
                //$user = $user->getUserFromEmail($_POST["email"]);
                //$user = $user->findBy(['where' => ['email' => $_POST['email']]], 'user');
                $user = $user->findOneBy(DBPREFIX.'user', ['id' => 11]);
                $user->generateToken();
               //Redirect
                Security::login($user);
                header('Location:/dashboard');
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
        $tokenVerification = "1536DHDCICuudz7";
        //$tokenVerification = Token::RandomString(75);
        $user = new UserModel();
        if(!empty($_POST)){
            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }
            //faire une vérif de l'email cf qques lignes plus bas
            $result = Validator::run($user->getFormRegister(), $_POST);
            if(empty($result)){
                $user->setEmail($_POST["email"]);
                $user->setFirstname($_POST["firstname"]);
                $user->setLastname($_POST["lastname"]);
                $user->setPassword($_POST["password"]);
                $user->setEmailToken($tokenVerification);
                $user->save();

                $mail = new PHPMailer();
                $options = [
                    'subject' => 'Validation de votre e-mail pour votre compte Animade',
                    'body' => "Bonjour, veuillez valider votre adresse email en cliquant sur le lien suivant : http:localhost/Core/PHPMailer/verifyAccount.php?email=".$user->getEmail()."&emailToken=".$tokenVerification,
                ];
                $mail->sendEmail($_POST["email"], $options);

                $view = new View("verifyAccount");
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

    public function verifyAccount()
    {
        $user = new UserModel();
        $id = $user->emailVerification();
        if(!empty($id)){
            $user->setId($id);
            $view = new View("dashboard");
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
        }
    }

}