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
                    Security::returnError(403, $e->getMessage());

                }
            }else {
                Security::returnError(403, implode("\r\n", $result));
            }
        } else {
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("personnage/newPersonnage");
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
        if(isset($parameters['personnage'])){
            $parameters['object'] = $parameters['personnage'];
        }
        Security::canAccessPersonnage($parameters['object'], $user);
        $view = new View("personnage/displayPersonnage");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("personnage", $parameters['object']);
        $view->assign("meta", [
            'script' => [
                "../dist/js/dataTable.js",
                "../dist/js/getUrlParameters.js",
                "../dist/js/displayPersonnage.js",
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
                    Security::returnError(403, $e->getMessage());

                }
            }else {
                Security::returnError(403, implode("\r\n", $result));
            }
        } else {
            $personnage = UrlHelper::getUrlParameters($_GET)['object'];
            $view = new View("personnage/updatePersonnage");
            $view->assign("personnage", $personnage);
        }
    }

    public function listPersonnages()
    {
        $personnage = new PersonnageModel();
        $personnages = $personnage->findManyBy(['statut' => 2]);
        $view = new View("personnage/listPersonnages");
        $view->assign("personnages", $personnages);
        $view->assign("meta",
            [
                'script' => ['../dist/js/dataTable.js'],
                'titre' => 'Personnages',

            ]);
    }

    public function delete()
    {
        $personnage = UrlHelper::getUrlParameters($_GET)['object'];
        $personnage->delete();
    }
}
