<?php

namespace App\Core;

use App\Core\View;
use App\Helpers\UrlHelper;
use App\Model\Categorie;
use App\Model\Chapitre;
use App\Model\Commentaire;
use App\Model\Page as PageModel;
use App\Model\Personnage;
use App\Model\User as UserModel;
use JetBrains\PhpStorm\NoReturn;

class Security
{

    /**
     * @param int $errorCode
     * @param string|null $message
     * @return void
     */
    #[NoReturn] public static function returnError(int $errorCode, string $message=null): void
    {
        http_response_code($errorCode);
        $view = new View("security/{$errorCode}", "without");
        if($message !== null){
            $view->assign("message", $message);
        }
        die;
    }


    public static function return403(string $message=null): bool
    {
        //var_dump( debug_backtrace()[1]['function']);die;
        http_response_code(403);
        $view = new View("security/403");
        if($message === null){
            $message = "Vous n'avez pas les droits d'accès à cette page";
        }
        $view->assign("message", $message);
        return false;
    }

    /**
     * @return bool|void
     */
    public static function isConnected()
    {
        if(!empty($_SESSION['user']['id']) && !empty($_SESSION['user']['token']) && self::verifyStatut($_SESSION['user']['status'])){
            $user = new UserModel();
            $response = $user->verifyToken($_SESSION['user']['id'], $_SESSION['user']['token']);
            if($response){
                return true;
            }
        }
        header('Location:/login');
        die();
    }

    /**
     * @return bool|void
     */
    public static function isUser()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] === 1){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function isAuthor()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] === 2){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function isAdmin()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] >= 3){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function isSuperAdmin()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] === 4){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool
     */
    public static function canAsAdmin(): bool
    {
        self::isConnected();
        return $_SESSION[ 'user' ][ 'role_id' ] >= 3;
    }


    /**
     * @param null $user
     * @return void
     */
    public static function login($user=null): void
    {
        $user->save();
        $_SESSION['user'] =
            [
                'id' => $user->getId(),
                'token' => $user->getToken(),
                'email' => $user->getEmail(),
                'role_id' => $user->getRoleId(),
                'status' => $user->getStatus(),
            ];
    }

    /**
     * @param UserModel $user
     * @return void
     */
    public static function updateCurrentUser(UserModel $user): void
    {
        $_SESSION['user'] =
            [
                'id' => $user->getId(),
                'token' => $user->getToken(),
                'email' => $user->getEmail(),
                'role_id' => $user->getRoleId(),
                'status' => $user->getStatus(),
            ];
    }

    /**
     * @return void
     */
    #[NoReturn] public static function logout(): void
    {
        session_destroy();
        header('Location:/login');
        die();
    }

    /**
     * @param PageModel $page
     * @param UserModel $user
     * @return void|bool
     */
    public static function canAccessPage(PageModel $page, UserModel $user)
    {
        if (self::verifyStatut($page->getStatut()) || $page->getAuteurId() === $user->getId()) {
            return true;
        }

        if($page->getStatut() === 1) {
            self::returnError(404);
        }
        self::returnError(403);
    }

    /**
     * @param Commentaire $commentaire
     * @return void|bool
     */
    public static function canAccessCommentaire(Commentaire $commentaire)
    {
        if (self::isAdmin() || 
            self::verifyStatut($commentaire->getStatut() && self::canDelete('commentaire'))) {
            return true;
        }

        if($commentaire->getStatut() === 1) {
            self::returnError(404);
        }
        self::returnError(403);
    }

    /**
     * @param Personnage $personnage
     * @return void|bool
     */
    public static function canAccessPersonnage(Personnage $personnage)
    {
        if( self::isAdmin() || self::verifyStatut($personnage->getStatut())){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @param Chapitre $chapitre
     * @return void|bool
     */
    public static function canAccessChapitre(Chapitre $chapitre)
    {
        if(self::isAdmin() || self::verifyStatut($chapitre->getStatut())){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @param Categorie $categorie
     * @return void|bool
     */
    public static function canAccessCategorie(Categorie $categorie)
    {
        if(self::isAdmin() || self::verifyStatut($categorie->getStatut())){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @param string $object
     * @return bool|void
     */
    public static function canDelete(string $object)
    {
        return match ($object) {
            'page' => self::canDeletePage(),
            'commentaire' => self::canDeleteCommentaire(),
            default => self::isSuperAdmin(),
        };
    }

    /**
     * @param string $object
     * @return bool|void
     */
    public static function canUpdate(string $object)
    {
        return match ($object) {
            'user' => self::canUpdateUser(),
            'page' => self::canUpdatePage(),
            default => self::isSuperAdmin(),
        };
    }

    /**
     * @return bool|void
     */
    public static function canDeletePage()
    {
        $page = UrlHelper::getUrlParameters($_GET)['object'];
        if(self::isAdmin() || $page->getAuteurId() === $_SESSION['user']['id']){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function canDeleteCommentaire()
    {
        $commentaire = UrlHelper::getUrlParameters($_GET)['object'];
        if(self::isAdmin() || $commentaire->getAuteurId() === $_SESSION['user']['id'] ){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function canUpdateUser()
    {
        $user = UrlHelper::getUrlParameters($_GET)['object'];
        if(self::isAdmin() || $user->getId() === $_SESSION['user']['id']){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function canUpdatePage()
    {
        $page = UrlHelper::getUrlParameters($_GET)['object'];
        if($page->getAuteurId() === $_SESSION['user']['id']){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @param object $object
     * @return string
     */
    public static function displayEditButton(object $object): string
    {
        switch (str_replace("App\\Model\\", "", get_class($object))){
            case 'Commentaire':
            case 'Page':
                if($object->getAuteurId() === $_SESSION['user']['id']){
                    return "yes";
                }
                break;
            default:
                return "no";
        }
        return "no";
    }

    /**
     * @return string
     */
    public static function displayCommentCreation(): string
    {
        if(self::getUser()->getRoleId() === 1){
            return "yes";
        }

        return "no";
    }

    /**
     * @param int $statut
     * @return bool|string
     */
    public static function verifyStatut(int $statut): bool|string
    {
        return $statut === 2;
    }

    /**
     * @return bool|void
     */
    public static function getUser()
    {
        if(!empty($_SESSION['user']['id'])){
            return (new UserModel())->setId($_SESSION['user']['id']);
        }
        return false;
    }

}
