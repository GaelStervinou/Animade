<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\Security;
use App\Core\Validator;
use App\Core\View;
use App\Helpers\MediaManager;
use App\Helpers\UrlHelper;
use App\Model\Personnage as PersonnageModel;
use App\Model\User as UserModel;

class Personnage
{

    public function create()
    {
        if (!empty($_POST)) {

            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }

            $personnage = new PersonnageModel();

            $result = Validator::run($personnage->getFormNewPersonnage(), $_POST);

            if (empty($result)) {
                try {
                    $personnage->beginTransaction();
                    $personnage->setNom($_POST['nom']);
                    $personnage->setStatut(2);

                    if(!empty($_POST['media']["tmp_name"])){
                        $personnage->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $personnage));
                    }

                    $id = $personnage->save();

                    $personnage->commit();
                    header('Location:/personnage?personnage_id='.$id);
                } catch (Exception $e) {
                    $personnage->rollback();
                    var_dump($e->getMessage());
                    die;
                }
            }
        } else {
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("personnage/newpersonnage");
            $personnage = new PersonnageModel();
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
            $view->assign("personnage", $personnage);
        }
    }

    public function read()
    {
        $user = Security::getUser();
        $parameters = UrlHelper::getUrlParameters($_GET);
        if(!empty($parameters['personnage'])){
            $parameters['object'] = $parameters['personnage'];
        }
        Security::canAccessPersonnage($parameters['object'], $user);
        $view = new View("personnage/displaypersonnage");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("personnage", $parameters['object']);
        $view->assign("meta", [
            'script' => [
                "../dist/js/datatable.js",
                "../dist/js/geturlparameters.js",
                "../dist/js/displaypersonnage.js",
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
            $personnage = UrlHelper::getUrlParameters($_GET)['object'];

            $result = Validator::run($personnage->getFormUpdatePersonnage(), $_POST);
            if (empty($result)) {
                try {
                    $personnage->beginTransaction();
                    $personnage->setNom($_POST['nom']);
                    $personnage->setStatut($_POST['statut']);

                    if(!empty($_POST['media']["tmp_name"])){
                        $personnage->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $personnage));
                    }

                    $personnage->save();

                    $personnage->commit();
                    header('Location:/personnage?personnage_id='.$personnage->getId());
                } catch (Exception $e) {
                    $personnage->rollback();
                    var_dump($e->getMessage());
                    die;
                }
            }
        } else {
            $personnage = UrlHelper::getUrlParameters($_GET)['object'];
            $view = new View("personnage/updatepersonnage");
            $view->assign("personnage", $personnage);
        }
    }

    public function listPersonnages()
    {
        $personnage = new PersonnageModel();
        $personnages = $personnage->findManyBy(['statut' => 2]);
        $view = new View("personnage/listpersonnages");
        $view->assign("personnages", $personnages);
        $view->assign("meta",
            [
                'script' => ['../dist/js/datatable.js'],
                'titre' => 'Personnages',

            ]);
    }

    public function delete()
    {
        $personnage = UrlHelper::getUrlParameters($_GET)['object'];
        $personnage->delete();
    }
}
