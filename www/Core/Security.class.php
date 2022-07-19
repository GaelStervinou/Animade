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
    }

    /**
     * @return bool|void
     */
    public static function isUser()
    {
        self::isConnected();
        if(self::getUser()->getRoleId() === 1){
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
        if(self::getUser()->getRoleId() === 2){
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
        if(self::getUser()->getRoleId() >= 3){
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
        if(self::getUser()->getRoleId() === 4){
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
        return self::getUser()->getRoleId() >= 3;
    }

    /**
     * @return bool
     */
    public static function canAsSuperAdmin(): bool
    {
        self::isConnected();
        return self::getUser()->getRoleId() === 4;
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
    }

    /**
     * @param PageModel|bool $page
     * @param UserModel|bool $user
     * @return void|bool
     */
    public static function canAccessPage(PageModel|bool $page, UserModel|bool $user)
    {
        if($page === false || $user === false){
            self::returnError(404);
        }
        if (self::verifyStatut($page->getStatut()) || $page->getAuteurId() === $user->getId()) {
            return true;
        }

        if($page->getStatut() === 1) {
            self::returnError(404);
        }
        self::returnError(403);
    }

    /**
     * @param Commentaire|bool $commentaire
     * @return void|bool
     */
    public static function canAccessCommentaire(Commentaire|bool $commentaire)
    {
        if($commentaire === false){
            self::returnError(404);
        }
        if (self::canAsAdmin() ||
            self::verifyStatut($commentaire->getStatut() && self::canDelete('commentaire'))) {
            return true;
        }

        if($commentaire->getStatut() === 1) {
            self::returnError(404);
        }
        self::returnError(403);
    }

    /**
     * @param Personnage|bool $personnage
     * @return void|bool
     */
    public static function canAccessPersonnage(Personnage|bool $personnage)
    {
        if($personnage === false){
            self::returnError(404);
        }
        if( self::canAsAdmin() || self::verifyStatut($personnage->getStatut())){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @param Chapitre|bool $chapitre
     * @return void|bool
     */
    public static function canAccessChapitre(Chapitre|bool $chapitre)
    {
        if($chapitre === false) {
            self::returnError(404);
        }
        if(self::canAsAdmin() || self::verifyStatut($chapitre->getStatut())){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @param Categorie|bool $categorie
     * @return void|bool
     */
    public static function canAccessCategorie(Categorie|bool $categorie)
    {
        if($categorie === false){
            self::returnError(404);
        }
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
            'media' => self::canUpdateMedia(),
            default => self::isSuperAdmin(),
        };
    }

    /**
     * @return bool|void
     */
    public static function canDeletePage()
    {
        $page = UrlHelper::getUrlParameters($_GET)['object'];
        if($page === false){
            self::returnError(404);
        }
        if(self::canAsAdmin() || $page->getAuteurId() === $_SESSION['user']['id']){
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
        if($commentaire === false){
            self::returnError(404);
        }
        if(self::canAsAdmin() || $commentaire->getAuteurId() === $_SESSION['user']['id'] ){
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
        if($user === false){
            self::returnError(404);
        }
        if(self::canAsAdmin() || $user->getId() === $_SESSION['user']['id']){
            return true;
        }
        self::returnError(403);
    }

    /**
     * @return bool|void
     */
    public static function canUpdateMedia()
    {
        $media = UrlHelper::getUrlParameters($_GET)['object'];
        if($media === false){
            self::returnError(404);
        }
        if(self::canAsAdmin() || $media->getUserId() === $_SESSION['user']['id']){
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
        if($page === false){
            self::returnError(404);
        }
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

    /**
     * @return bool|void
     */
    public static function canBoot()
    {
        if(empty(self::getConfig())){
            return true;
        }

        self::returnError(403, "Cette page n'est pas accessible");
    }

    /**
     * @return string|null
     */
    public static function getConfig(): ?string
    {
        return file_get_contents('conf.inc.php');
    }

}
