<?php

namespace App\Core;

use App\Helpers\UrlHelper;
use App\Model\Categorie;
use App\Model\Commentaire;
use App\Model\Page as PageModel;
use App\Model\Personnage;
use App\Model\User as UserModel;
use App\Core\View;

class Security
{

    public static function return403(string $message=null)
    {
        http_response_code(403);
        $view = new View("security/403");
        if($message !== null){
            $view->assign("message", $message);
        }
        die;
    }

    public static function isConnected()
    {
        if(!empty($_SESSION['user']['id']) && !empty($_SESSION['user']['token']) && self::verifyStatut($_SESSION['user']['status'])){
            $user = new UserModel();
            $response = $user->verifyToken($_SESSION['user']['id'], $_SESSION['user']['token']);
            if($response == true){
                return true;
            }
        }
        header('Location:/login');
        die();
    }

    public static function isUser()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] == 1){
            return true;
        }
        self::return403();
    }

    public static function isAuthor()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] == 2){
            return true;
        }
        self::return403();
    }

    public static function isAdmin()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] >= 3){
            return true;
        }
        self::return403();
    }

    public static function isSuperAdmin()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] == 4){
            return true;
        }
        self::return403();
    }


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

    public static function canAccessPage(PageModel $page, UserModel $user)
    {
        if(self::verifyStatut($page->getStatut()) || $page->getAuteurId() == $user->getId()){
            return true;
        }elseif($page->getStatut() == 1){
            http_response_code(404);
            die();
        }
        self::return403();
    }

    public static function canAccessCommentaire(Commentaire $commentaire, UserModel $user)
    {
        if(self::verifyStatut($commentaire->getStatut() && self::canDelete('commentaire'))
            || self::isAdmin()){
            return true;
        }elseif($commentaire->getStatut() == 1){
            http_response_code(404);
            die();
        }
        self::return403();
    }

    public static function canAccessPersonnage(Personnage $personnage, UserModel $user)
    {
        if(self::verifyStatut($personnage->getStatut()) || self::isAdmin()){
            return true;
        }
        self::return403();
    }

    public static function canAccessCategorie(Categorie $categorie, UserModel $user)
    {
        if(self::verifyStatut($categorie->getStatut()) || self::isAdmin()){
            return true;
        }
        self::return403();
    }

    public static function canDelete(string $object)
    {
        switch ($object){
            case 'page':
                return Security::canDeletePage();
                break;
            case  'commentaire':
                return Security::canDeleteCommentaire();
                break;
            default:
                return Security::isSuperAdmin();
        }
    }

    public static function canUpdate(string $object)
    {
        switch ($object){
            case 'user':
                return Security::canUpdateUser();
                break;
            case 'page':
                return Security::canUpdatePage();
            default:
                return Security::isSuperAdmin();
        }
    }

    public static function canDeletePage()
    {
        $page = UrlHelper::getUrlParameters($_GET)['object'];
        if($page->getAuteurId() == $_SESSION['user']['id'] || Security::isAdmin()){
            return true;
        }
        self::return403();
    }

    public static function canDeleteCommentaire()
    {
        $commentaire = UrlHelper::getUrlParameters($_GET)['object'];
        if($commentaire->getAuteurId() == $_SESSION['user']['id'] || Security::isAdmin()){
            return true;
        }
        self::return403();
    }

    public static function canUpdateUser()
    {
        $user = UrlHelper::getUrlParameters($_GET)['object'];
        if($user->getId() == $_SESSION['user']['id'] || Security::isAdmin()){
            return true;
        }
        self::return403();
    }

    public static function canUpdatePage()
    {
        $page = UrlHelper::getUrlParameters($_GET)['object'];
        if($page->getAuteurId() == $_SESSION['user']['id']){
            return true;
        }
        self::return403();
    }

    public static function displayEditButton(object $object)
    {
        switch (str_replace("App\\Model\\", "", get_class($object))){
            case 'Commentaire':
            case 'Page':
                if($object->getAuteurId() == $_SESSION['user']['id']){
                    return "yes";
                }
                break;
            default:
                return "no";
        }
        return "no";
    }
    public static function displayCommentCreation()
    {
        if(Security::getUser()->getRoleId() == 1){
            return "yes";
        }else{
            return "no";
        }
    }

    public static function verifyStatut($statut)
    {
        if($statut == 2){
            return true;
        }else{
            return false;
        }
    }

    public static function getUser()
    {
        $user = new UserModel();
        return $user->setId($_SESSION['user']['id']);
    }

}
