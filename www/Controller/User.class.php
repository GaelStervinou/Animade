<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Core\PHPMailer;
use App\Core\SMTP;
use App\Core\Exception;
use App\Core\Security;

use App\Helpers\UrlHelper;
use App\Model\User as UserModel;

class User
{

    public function login()
    {
        $user = new UserModel();
        if (!empty($_POST["password"]) && !empty($_POST["email"])) {
            $check_password = $user->login($_POST["email"], $_POST["password"]);
            if ($check_password == true) {
                $user = $user->getUserFromEmail($_POST["email"]);
                $user->generateToken();
                Security::login($user);

                if (Security::canAsAdmin() === true) {
                    header('Location:/admin/dashboard');
                } else {
                    header('Location:/');
                }
            } else {
                echo "mot de passe incorrect";
            }
        } else {
            $view = new View("Login");
            $view->assign("titleSeo", "Se connecter au site");
            $view->assign("user", $user);
        }
    }

    public function logout()
    {
        Security::logout();
    }

    public function register()
    {
        $tokenVerification = "1536DHDCICuudz7";
        $user = new UserModel();
        if (!empty($_POST)) {
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $info) {
                    $_POST[$name] = $info;
                }
            }

            $result = Validator::run($user->getFormRegister(), $_POST);
            if (empty($result)) {
                $user->setEmail($_POST["email"]);
                $user->setFirstname($_POST["firstname"]);
                $user->setLastname($_POST["lastname"]);
                $user->setPassword($_POST["password"]);
                $user->setStatus(1);
                $user->setRoleId(1);
                $user->setEmailToken($tokenVerification);
                $user->save();

                $mail = new PHPMailer();
                $options = [
                    'subject' => 'Validation de votre e-mail pour votre compte Animade',
                    'body' => "Bonjour, veuillez valider votre adresse email en cliquant sur le lien suivant : http:localhost/Core/PHPMailer/verifyAccount.php?email=" . $user->getEmail() . "&emailToken=" . $tokenVerification,
                ];
                $mail->sendEmail($_POST["email"], $options);

                $view = new View("verifyAccount");
            } else {
                echo "Formulaire invalide :<br>";
                foreach ($result as $error) {
                    echo $error . "<br>";
                }
                $user = new UserModel();
                $view = new View("register");
            }
        } else {
            $user = new UserModel();
            $view = new View("register");
        }
        $view->assign('user', $user);
    }

    public function verifyAccount()
    {
        $user = new UserModel();

        $id = $user->emailVerification();
        if (!empty($id)) {
            $user = $user->setId($id);
            try {
                $user->beginTransaction();
                $user->setStatus(2);
                $user->save();
                $_SESSION['user']['status'] = 2;
                $view = new View("dashboard");
                $view->assign("firstname", $user->getFirstname());
                $view->assign("lastname", $user->getLastname());
                $user->commit();
            } catch (Exception $e) {
                $user->rollback();
                var_dump($e->getMessage());
                die;
            }
        }
    }

    public function update()
    {
        if (!empty($_POST)) {
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $info) {
                    $_POST[$name] = $info;
                }
            }
            $user = UrlHelper::getUrlParameters($_GET)['object'];

            $result = Validator::run($user->getFormUpdate($user->getId()), $_POST);
            if (empty($result)) {
                try {
                    $user->beginTransaction();
                    $attributes = ['firstname', 'lastname', 'password', 'role_id', 'status'];
                    if (!empty($_POST['firstname'])) {
                        $user->setFirstname($_POST["firstname"]);
                    }
                    if (!empty($_POST['lastname'])) {
                        $user->setLastname($_POST["lastname"]);
                    }
                    if (!empty($_POST['password'])) {
                        $user->setPassword($_POST["password"]);
                    }
                    if (!empty($_POST['status'])) {
                        $user->setStatus($_POST['status']);
                    }
                    if (!empty($_POST['role_id'])) {
                        if ($_POST['role_id'] == 4) {
                            http_response_code(403);
                            die;
                        }
                        $user->setRoleId($_POST["role_id"]);
                    }
                    $user->save();
                    $user->commit();
                    if (Security::isAdmin()) {
                        header('Location:/admin/users');
                    } else {
                        header('Location:/');
                    }
                } catch (Exception $e) {
                    $user->rollback();
                    var_dump($e->getMessage());
                    die;
                }
            }
        } else {
            $user = UrlHelper::getUrlParameters($_GET)['object'];
            $view = new View("user/updateUser");
            $view->assign("user", $user);
        }
    }

    public function delete()
    {
        $user = UrlHelper::getUrlParameters($_GET)['object'];
        try {
            $user->beginTransaction();
            $user->setStatus(-1);
            $user->save();
            $user->commit();
        } catch (Exception $e) {
            $user->rollback();
            var_dump($e->getMessage());
            die;
        }
    }
    public function passwordForgotten()
    {
        $user = new UserModel();
        $view = new View("passwordForgotten");
        $view->assign("user", $user);
        
    
        $mail = new PHPMailer(); 
        $options = [
            'subject' => 'Réinitialisation du votre mot de passe de votre compte Animade ',
            'body' => "Bonjour, veuillez modifier votre mot de passe en cliquant sur le lien suivant : http:localhost/Core/PHPMailer/verifyAccount.php?email=".$user->getEmail()."&emailToken=".$tokenVerification,
        ];
        $mail->sendEmail($_POST["email"], $options);
        
        
        // $mail = new PHPMailer();
        //         $options = [
        //             'subject' => 'Réinitialisation du votre mot de passe de votre compte Animade ',
        //             'body' => "Bonjour, veuillez valider la réinitialisation de votre mot de passe en cliquant sur le lien suivant : http:localhost/Core/PHPMailer/verifyAccount.php?email=".$user->getEmail()."&emailToken=".$tokenVerification,
        //         ];
        //         $mail->sendEmail($_POST["email"], $options);
        


    }

}
