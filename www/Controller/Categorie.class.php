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
                    if(!empty($_POST['parent_id'])){
                        $categorie->setParentId($_POST['parent_id']);
                    }

                    $id = $categorie->save();

                    $categorie->commit();
                }catch (Exception $e) {
                    $categorie->rollback();
                    Security::returnError(422);
                }
                header('Location:/categorie?categorie_id='.$id);
            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        }else{
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("categorie/newcategorie");
            $categorie = new CategorieModel();
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
            $view->assign("categorie", $categorie);
        }
    }

    public function read()
    {
        $parameters = UrlHelper::getUrlParameters($_GET);
        if(isset($parameters['categorie'])){
            $parameters['object'] = $parameters['categorie'];
        }
        Security::canAccessCategorie($parameters['object']);
        $view = new View("categorie/displaycategorie");

        $view->assign("categorie", $parameters['object']);
        $view->assign("meta", [
            'script' => [
                "../dist/js/datatable.js",
                "../dist/js/geturlparameters.js",
                "../dist/js/displaycategorie.js"
            ],
        ]);
    }

    public function update()
    {
        if (!empty($_POST)) {
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $info) {
                    $_POST[$name] = $info;
                }
            }
            $categorie = UrlHelper::getUrlParameters($_GET)['object'];

            $result = Validator::run($categorie->getFormUpdateCategorie(), $_POST);
            if (empty($result)) {
                try {
                    $categorie->beginTransaction();
                    $categorie->setNom($_POST['nom']);
                    $categorie->setStatut($_POST['statut']);
                    $categorie->setDescription($_POST['description']);
                    if(!empty($_POST['parent_id'])){
                        $categorie->setParentId($_POST['parent_id']);
                    }

                    $categorie->save();

                    $categorie->commit();
                    header('Location:/categorie?categorie_id='.$categorie->getId());
                } catch (Exception $e) {
                    $categorie->rollback();
                    Security::returnError(422);

                }
            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        } else {
            $categorie = UrlHelper::getUrlParameters($_GET)['object'];
            $view = new View("categorie/updatecategorie");
            $view->assign("categorie", $categorie);
        }
    }

    public function listCategories()
    {
        $categorie = new CategorieModel();
        $categories = $categorie->findManyBy(['statut' => 2]);
        $view = new View("categorie/listcategories");
        $view->assign("categories", $categories);
        $view->assign("meta",
            [
                'script' => ['../dist/js/datatable.js'],
                'titre' => 'Catégories',

            ]);
    }

    public function delete()
    {
        $categorie = UrlHelper::getUrlParameters($_GET)['object'];
        $categorie->delete();
    }
}