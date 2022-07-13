<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\Validator;
use App\Helpers\MediaManager;
use App\Helpers\UrlHelper;
use App\Model\Chapitre as ChapitreModel;
use Exception;
use App\Core\View;

class Chapitre{

    public function create()
    {
        if (!empty($_POST)) {

            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }

            $chapitre = new ChapitreModel();

            $result = Validator::run($chapitre->getNewChapitreForm(), $_POST);

            if (empty($result)) {
                try {
                    $chapitre->beginTransaction();
                    $chapitre->setTitre($_POST['titre']);
                    $chapitre->setStatut(2);

                    if(!empty($_POST['media']["tmp_name"])){
                        $chapitre->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $chapitre));
                    }

                    $id = $chapitre->save();

                    $chapitre->commit();
                    header('Location:/chapitre?chapitre='.$chapitre->getTitre());
                } catch (Exception $e) {
                    $chapitre->rollback();
                    var_dump($e->getMessage());
                    die;
                }
            }
        } else {
            $user = Security::getUser();
            $view = new View("chapitre/newChapitre");
            $chapitre = new ChapitreModel();
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
            $view->assign("chapitre", $chapitre);
        }
    }

    public function read()
    {
        $user = Security::getUser();
        $parameters = UrlHelper::getUrlParameters($_GET);
        if(!empty($parameters['chapitre'])){
            $parameters['object'] = $parameters['chapitre'];
        }
        Security::canAccessChapitre($parameters['object'], $user);
        $view = new View("chapitre/displayChapitre");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("chapitre", $parameters['object']);
        $view->assign("meta", [
            'script' => [
                "../dist/js/getUrlParameters.js",
                "../dist/js/displayChapitre.js"
            ],
        ]);
    }

}