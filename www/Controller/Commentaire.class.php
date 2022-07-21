<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\Validator;
use App\Core\View;
use App\Core\Security;
use App\Helpers\MediaManager;
use App\Helpers\UrlHelper;
use App\Model\User as UserModel;
use App\Model\Page as PageModel;
use App\Model\Commentaire as CommentaireModel;
use App\Model\Signalement;

class Commentaire{

    public function create()
    {
        if(!empty($_POST)){
            $commentaire = new CommentaireModel();

            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }

            $result = Validator::run($commentaire->getFormNewCommentaire(), $_POST, 2);

            if(empty($result)){
                try {
                    $commentaire->beginTransaction();
                    $commentaire->setContenu(Validator::sanitizeWysiwyg($_POST['contenu']));
                    $commentaire->setStatut(2);
                    $commentaire->setAuteurId($_SESSION['user']['id']);
                    $commentaire->setPageId($_POST['page_id']);
                    if(!empty($_POST['commentaire_id']) && Validator::checkIfCanResponseToComment($_POST['commentaire_id']) === true){
                        $commentaire->setCommentaireId($_POST['commentaire_id']);
                    }

                    if(!empty($_POST['media']["tmp_name"])){
                        $commentaire->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $commentaire));
                    }

                    $commentaire->save();
                    $commentaire->commit();
                    header("Location:{$_SERVER['HTTP_REFERER']}");

                }catch (Exception $e) {
                    $commentaire->rollback();
                    Security::returnError(422);
                }
            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        }else{
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("page/newpage");
            $page = new PageModel();
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
            $view->assign("page", $page);
        }
    }

    public function listCommentaires()
    {
        $commentaire = new PageModel();
        $view = new View("page/listpages");

        $commentaires = $commentaire->findManyBy(['statut' => 2]);

        $view->assign("commentaire", $commentaires);
    }

    public function read()
    {
        $user = Security::getUser();
        $parameters = UrlHelper::getUrlParameters($_GET);

        Security::canAccessCommentaire($parameters['object']);
        $view = new View("commentaire/displaycommentaire");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("commentaire", $parameters['object']);
    }

    public function delete()
    {
        $commentaire = UrlHelper::getUrlParameters($_GET)['object'];
        if(Security::canDelete('commentaire')){
            $commentaire->delete();
        }

        $signalements = new Signalement();
        $signalements = $signalements->findManyBy(['commentaire_id' => $commentaire->getId()]);
        foreach($signalements as $signalement){
            $signalement->delete();
        }
        header("Location:{$_SERVER['HTTP_REFERER']}");
    }
}