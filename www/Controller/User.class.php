<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Core\PHPMailer;
use App\Core\SMTP;
use App\Core\Exception;
use App\Core\Security;

use App\Helpers\MediaManager;
use App\Helpers\UrlHelper;
use App\Model\User as UserModel;
use JetBrains\PhpStorm\NoReturn;

class User
{

    /**
     * @return void
     */
    public function login(): void
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
            }else {
                Security::returnError(403, "Mot de passe incorrect");
            }
        } else {
            session_destroy();
            $view = new View("login");
            $view->assign("meta", [
                'titre' => 'Se connecter',
            ]);
            $view->assign("user", $user);
        }
    }

    /**
     * @return void
     */
    #[NoReturn] public function logout(): void
    {
        Security::logout();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $user = new UserModel();
        if (!empty($_POST)) {
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $info) {
                    $_POST[$name] = $info;
                }
            }

            $result = Validator::run($user->getFormRegister(), $_POST);
            if (empty($result)) {
                try{
                    $user->beginTransaction();

                    $user->setEmail($_POST["email"]);
                    $user->setFirstname($_POST["firstname"]);
                    $user->setLastname($_POST["lastname"]);
                    $user->setPassword($_POST["password"]);
                    $user->setStatus(1);
                    $user->setRoleId(1);
                    $user->generateEmailToken();
                    $mail = new PHPMailer();
                    $options = [
                        'subject' => 'Validation de votre e-mail pour votre compte Animade',
                        'body' => "Bonjour, veuillez valider votre adresse email en cliquant sur le lien suivant : http://{$_SERVER['HTTP_HOST']}/verifyAccount?email={$user->getEmail()}&emailToken={$user->getEmailToken()}",
                    ];
                    $mail->sendEmail($_POST["email"], $options);

                    $user->save();
                    $user->commit();

                    $view = new View("verifyaccount", "without");

                }catch(Exception $e){
                    echo $e->getMessage();
                }

            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        } else {
            $user = new UserModel();
            $view = new View("register");
        }
        $view->assign('user', $user);
    }

    /**
     * @return void
     */
    public function verifyAccount(): void
    {
        $user = (new UserModel())->emailVerification();
        if ($user !== false) {
            try {
                $user->beginTransaction();
                $user->setStatus(2);
                $user->generateEmailToken();
                $user->generateToken();
                $user->save();
                $user->commit();

                Security::updateCurrentUser($user);

                $view = new View("user/verifiedaccount", "without");
                $view->assign("meta", [
                    'titre' => 'Votre compte est validé',
                    'script' => [
                        '../dist/js/verifiedaccount.js',
                    ]
                ]);
            } catch (Exception $e) {
                $user->rollback();
                Security::returnError(422, $e->getMessage());
            }
        }else{
            Security::returnError(404);
        }
    }

    /**
     * @return void
     */
    public function update(): void
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
                        $user->setRoleId($_POST["role_id"]);
                    }
                    if(!empty($_POST['media']['tmp_name'])){
                        $user->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $user));
                    }elseif(!empty($_POST['select_media'])){
                        $user->setMediaId($_POST['select_media']);
                    }
                    $user->save();
                    $user->commit();
                    if (Security::canAsAdmin() === true) {
                        header('Location:/admin/users');
                    } else {
                        header('Location:/user?user_id='.$user->getId());
                    }
                } catch (Exception $e) {
                    $user->rollback();
                    Security::returnError(422, $e->getMessage());
                }
            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        } else {
            $userUpdate = UrlHelper::getUrlParameters($_GET)['object'];
            $view = new View("user/updateuser");
            $view->assign("userUpdate", $userUpdate);
        }
    }

    public function forgottenPassword()
    {
        if (!empty($_POST)) {
            $user = new UserModel();

            $result = Validator::run($user->getEmailPasswordForgottenForm(), $_POST);
            if(empty($result)){
                $user = $user->getUserFromEmail($_POST["email"]);
            }
            if(!empty($user))
            {
                try{
                    $user->beginTransaction();
                    $user->generateMdpToken();
                    $user->save();

                    $mail = new PHPMailer();
                    $options = [
                        'subject' => 'Récupération de votre mot de passe pour votre compte Animade',
                        'body' => "Bonjour, veuillez cliquer sur le lien suivant : http://{$_SERVER['HTTP_HOST']}/updatePassword?email={$user->getEmail()}&mdpToken={$user->getMdpToken()}
                            afin de renouveler votre mot de passe Animade.",
                    ];
                    $mail->sendEmail($_POST["email"], $options);

                    $user->commit();

                    $view = new View("user/forgottenpassword");
                    $view->assign("message", "Un email vous a été envoyé pour changer votre mot de passe.");

                }catch (Exception $e) {
                    $user->rollback();
                    Security::returnError(422, $e->getMessage());

                }
            }
        } else {
            $user = new UserModel();
            $view = new View("user/forgottenpassword");
            $view->assign("user", $user);
        }
    }

    public function updatePassword()
    {
        if (!empty($_POST)) {
            $user = new UserModel();

            $result = Validator::run($user->getUpdatePasswordForm(), $_POST);
            if(empty($result)){
                $user = $user->getUserFromEmail($_GET["email"]);
            }
            if(!empty($user) && $user->getMdpToken() === $_GET["mdpToken"])
            {
                try{
                    $user->beginTransaction();
                    $user->setPassword($_POST["password"]);
                    $user->generateMdpToken();
                    $user->save();
                    $user->commit();

                    Security::login($user);

                    header('Location:/');

                }catch (Exception $e) {
                    $user->rollback();
                    Security::returnError(422, $e->getMessage());

                }
            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        } else {
            $user = new UserModel();
            $user = $user->getUserFromEmail($_GET["email"]);
            if(!empty($user) && $user->getMdpToken() === $_GET["mdpToken"]) {
                $view = new View("user/updatepassword");
                $view->assign("user", $user);
            }else{
                Security::returnError(403, "Le lien de réinitialisation de mot de passe n'est pas valide.");
            }

        }
    }

    public function read()
    {
        $user = UrlHelper::getUrlParameters($_GET)['object'];
        if(!empty($user)) {
            $view = new View("user/displayuser");
            $view->assign("user", $user);
        }else{
            Security::returnError(403);
        }

    }

    public function listAuteurs()
    {
        $auteurs = (new UserModel())->findManyBy(['status' => 2, 'role_id' => 2]);
        $view = new View("auteur/listauteurs");
        $view->assign("auteurs", $auteurs);
        $view->assign("meta",
            [
                'script' => ['../dist/js/datatable.js'],
                'titre' => 'Catégories',
            ]);
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $user = UrlHelper::getUrlParameters($_GET)['object'];
        try {
            $user->beginTransaction();
            $user->setStatus(-1);
            $user->save();
            $user->commit();
            if(Security::canAsAdmin() === true && Security::getUser()->getId() !== $user->getId()){
                header('Location:/admin/users');
            } else {
                header('Location:/logout');
            }
        } catch (Exception $e) {
            $user->rollback();
            Security::returnError(422, $e->getMessage());
        }
    }
}
