<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\Security;
use App\Core\Validator;
use App\Core\View;
use App\Helpers\UrlHelper;
use App\Model\Categorie as CategorieModel;
use App\Model\User as UserModel;
use App\Model\Page as PageModel;

class Categorie{

    public function create()
    {
        if(!empty($_POST)){
            $categorie = new CategorieModel();

            $result = Validator::run($categorie->getFormNewCategorie(), $_POST);
            if(empty($result)){
                try {
                    $categorie->beginTransaction();
                    $categorie->setNom($_POST['nom']);
                    $categorie->setDescription($_POST['description']);
                    $categorie->setStatut($_POST['statut']);
                    $categorie->setParentId($_POST['parent_id']);

                    $id = $categorie->save();

                    $categorie->commit();
                }catch (Exception $e) {
                    $categorie->rollback();
                    var_dump($e->getMessage());die;
                }
                header('Location:/categorie?categorie_id='.$id);
            }
        }else{
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("categorie/newCategorie");
            $categorie = new CategorieModel();
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
            $view->assign("categorie", $categorie);
        }
    }

    public function read()
    {
        $user = new UserModel();
        $user = $user->setId($_SESSION['user']['id']);
        $parameters = UrlHelper::getUrlParameters($_GET);

        Security::canAccessCategorie($parameters['object'], $user);
        $view = new View("categorie/displayCategorie");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("categorie", $parameters['object']);
        $view->assign("pages", $parameters['object']->getPages());
    }

    public function listCategories()
    {
        $categorie = new CategorieModel();
        $categories = $categorie->findManyBy(['statut' => 2]);
        $view = new View("categorie/listCategories");
        $view->assign("categories", $categories);
    }

    public function delete()
    {
        $categorie = UrlHelper::getUrlParameters($_GET)['object'];
        $categorie->delete();
    }
}