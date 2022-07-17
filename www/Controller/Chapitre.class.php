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
            $view = new View("chapitre/newchapitre");
            $chapitre = new ChapitreModel();
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
        $view = new View("chapitre/displaychapitre");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("chapitre", $parameters['object']);
        $view->assign("meta", [
            'script' => [
                "../dist/js/datatable.js",
                "../dist/js/geturlparameters.js",
                "../dist/js/displaychapitre.js"
            ],
        ]);
    }

    public function update()
    {
        if (!empty($_POST)) {

            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }

            $chapitre = (new ChapitreModel())->setId($_GET['chapitre_id']);
            $result = Validator::run($chapitre->getUpdateChapitreForm(), $_POST);
            if (empty($result)) {
                try {
                    $chapitre->beginTransaction();
                    $chapitre->setTitre($_POST['titre']);
                    $chapitre->setStatut(2);
                    if(!empty($_POST['media']['tmp_name'])){
                        if($chapitre->hasMedia() === true){
                            $chapitre->getMedia()->delete();
                        }

                        $chapitre->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $chapitre));
                    }elseif(!empty($_POST['select_media'])){
                        if($chapitre->hasMedia() === true){
                            $chapitre->getMedia()->delete();
                        }
                        $chapitre->setMediaId($_POST['select_media']);
                    }

                    $chapitre->save();
                    $chapitre->commit();
                    header('Location:/chapitre?chapitre='.$chapitre->getTitre());
                } catch (Exception $e) {
                    $chapitre->rollback();
                    var_dump($e->getMessage());
                    die;
                }
            }
        } else {
            $view = new View("chapitre/updateChapitre");
            $chapitre = (new ChapitreModel())->setId($_GET['chapitre_id']);
            $view->assign("chapitre", $chapitre);
        }
    }

    public function listChapitres()
    {
        $chapitre = new ChapitreModel();
        $chapitres = $chapitre->findManyBy(['statut' => 2]);
        $view = new View("chapitre/listchapitres");
        $view->assign("chapitres", $chapitres);
        $view->assign("meta",
            [
                'script' => ['../dist/js/datatable.js'],
                'titre' => 'Chapitres',

            ]);
    }

}