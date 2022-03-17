<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Core\PHPMailer;
use App\Core\SMTP;
use App\Core\Exception;

use App\Model\User as UserModel;

//hypotétiquement;

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
            //faire une vérif de l'email cf qques lignes plus bas
            $result = Validator::run($user->getFormRegister(), $_POST);
            if(empty($result)){
                $user->setEmail($_POST["email"]);
                $user->setFirstname($_POST["firstname"]);
                $user->setLastname($_POST["lastname"]);
                $user->setPassword($_POST["password"]);
                $user->save();
                //envoi de mail de vérification avec un token spécial
                //ce token sera envoyé en paramètre vers une route /account_verification
                // une fois que le user a cliqué sur le lien envoyé par mail ( avec le paramètre ), on appelle une fonction
                // verif_email() qui passe le statut du user de 0 à 1 si le paramètre = token d'email verification
                // par la suite, on vérifiera lors de l'inscription si l'email existe déjà ou pas.
                // on choisit le champs token pour identifier et valide rle compte du user
                $mail = new PHPMailer();
                try {
                    //Configuration
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Informations de debug

                    // On configure le SMTP
                    $mail->isSMTP();
                    $mail->Host ="ssl://smtp.gmail.com";
                    $mail->Port = 465;
                    $mail->SMTPAuth = true;
                    $mail->Username = "thomasesgipa@gmail.com";
                    $mail->Password = "gfGYF3XD8@dgDcFJ";

                    //Charset
                    $mail->Charset = "utf-8";

                    //Destinataires: à remplacer par la varibale du mail qui est rensigné au moment de l'inscription
                    $mail->addAddress("stervinou.g36@gmail.com");

                    //Expéditeur
                    $mail->setFrom("thomasesgipa@gmail.com");

                    //Contenu
                    $mail->Subject = "Test envoi validation adresse email";
                    $mail->Body = "Bonjour, veuillez valider votre adresse email en cliquant sur le lien suivant.";

                    //On envoie le mail
                    $mail->send();
                    echo "Mail envoyé correctement";

                }catch(Exception $e){
                    echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";

                }

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
            $mail = new PHPMailer();
            try {
                //Configuration
                $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Informations de debug

                // On configure le SMTP
                $mail->isSMTP();
                $mail->Host ="ssl://smtp.gmail.com";
                $mail->Port = 465;
                $mail->SMTPAuth = true;
                $mail->Username = "thomasesgipa@gmail.com";
                $mail->Password = "gfGYF3XD8@dgDcFJ";

                //Charset
                $mail->Charset = "utf-8";

                //Destinataires: à remplacer par la varibale du mail qui est rensigné au moment de l'inscription
                $mail->addAddress("stervinou.g36@gmail.com");

                //Expéditeur
                $mail->setFrom("thomasesgipa@gmail.com");

                //Contenu
                $mail->Subject = "Test envoi validation adresse email";
                $mail->Body = "Bonjour, veuillez valider votre adresse email en cliquant sur le lien suivant.";

                //On envoie le mail
                $mail->send();
                echo "Mail envoyé correctement";

            }catch(Exception $e){
                echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";

            }
            $user = new UserModel();
            $view = new View("register");
        }
        $view->assign('user', $user);
    }

}