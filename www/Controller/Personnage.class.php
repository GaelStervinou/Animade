<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\Security;
use App\Core\Validator;
use App\Core\View;
use App\Helpers\UrlHelper;
use App\Model\Personnage as PersonnageModel;
use App\Model\User as UserModel;

class Personnage
{

    public function create()
    {
        if (!empty($_POST)) {
            $personnage = new PersonnageModel();

            $result = Validator::run($personnage->getFormNewPersonnage(), $_POST);

            if (empty($result)) {
                try {
                    $personnage->beginTransaction();
                    $personnage->setNom($_POST['nom']);
                    $personnage->setStatut(2);

                    $personnage->save();

                    $personnage->commit();
                    header('Location:/');
                } catch (Exception $e) {
                    $personnage->rollback();
                    var_dump($e->getMessage());
                    die;
                }
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
        $user = new UserModel();
        $user = $user->setId($_SESSION['user']['id']);
        $parameters = UrlHelper::getUrlParameters($_GET);

        Security::canAccessPersonnage($parameters['object'], $user);
        $view = new View("personnage/displayPersonnage");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("personnage", $parameters['object']);
    }

    public function delete()
    {
        $personnage = UrlHelper::getUrlParameters($_GET)['object'];
        $personnage->delete();
    }
}
