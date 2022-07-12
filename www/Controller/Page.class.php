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
use App\Model\Personnage;
use App\Model\Commentaire as CommentaireModel;

class Page{

    public function create()
    {
        if(!empty($_POST)){
            $page = new PageModel();

            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }

            $result = Validator::run($page->getFormNewPage(), $_POST);

            if(empty($result)){
                try {
                    $page->beginTransaction();
                    $page->setTitre($_POST['titre']);
                    $page->setDescription($_POST['description']);
                    $page->setSlug(Validator::sanitizeSlug($_POST['slug']));
                    $page->setContenu(Validator::sanitizeWysiwyg($_POST['contenu']));
                    $page->setStatut(1);
                    $page->setAuteurId($_SESSION['user']['id']);

                    // new
                    $page->setStatut($_POST['statut']);
                    $page->setPersonnageId($_POST['personnage_id']);
                    $page->setChapitreId($_POST['chapitre_id']);
                    $page->setCategorieId(($_POST['categorie_id']));

                    if(!empty($_POST['media']['tmp_name'])){
                        $page->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $page));
                    }

                    $page->save();
                    $page->commit();
                }catch (Exception $e) {
                    $page->rollback();
                    var_dump($e->getMessage());die;
                }
            }
        }else{
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("page/newPage");
            $page = new PageModel();
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
            $view->assign("page", $page);
        }
    }

    public function listPages()
    {
        try{
            $page = new PageModel();
            $view = new View("page/listPages");
            if(!empty($_GET)){
                if(!empty($_GET['personnage'])){
                    $personnage = new Personnage();
                    $_GET['personnage_id'] = $personnage->getPersonnageFromNom($_GET['personnage'])->getId();
                    unset($_GET['personnage']);
                }

                $parameters = $_GET;


                $parameters['statut'] = 2;
                $pages = $page->findManyBy($parameters);
                $query = UrlHelper::getSearch($_GET);

                $recherche = [];
                foreach ($query as $param => $value){
                    $recherche[] = ucfirst($param). ': ' .$value;
                }
                $recherche = implode(', ', $recherche);
                $view->assign("recherche", $recherche);
            }else{
                $pages = $page->findManyBy(['statut' => 2]);
            }
            $view->assign("pages", $pages);
        }catch(Exception $e){
            die('test');
        }
    }

    public function read()
    {
        $user = Security::getUser();
        $parameters = UrlHelper::getUrlParameters($_GET);
        Security::canAccessPage($parameters['page'], $user);
        $can_comment = Security::displayCommentCreation();
        $view = new View("page/displayPage");
        $view->assign("firstname", $user->getFirstname());
        $view->assign("lastname", $user->getLastname());
        $view->assign("page", $parameters['page']);
        $view->assign("can_edit", Security::displayEditButton($parameters['page']));
        $view->assign("can_comment", $can_comment);
        $view->assign("user", $user);
        $view->assign("user_like", $parameters['page']->currentUserLike());

        $meta = [
            'script' => ['../dist/js/displayPageSignalComment.js']
        ];
        if($user->getRoleId() == 1) {
            $meta['script'][] = "../dist/js/displayPage.js";
        }

        $view->assign("meta", $meta);

        if($can_comment === "yes"){
            $view->assign("commentaire", new CommentaireModel());
        }
    }

    public function update()
    {
        if(!empty($_POST)){
            if(!empty($_FILES)){
                foreach($_FILES as $name => $info){
                    $_POST[$name] = $info;
                }
            }
            $page = UrlHelper::getUrlParameters($_GET)['object'];
            $result = Validator::run($page->getFormUpdatePage(), $_POST);

            if(empty($result)){
                try{
                    $page->beginTransaction();
                    foreach ($_POST as $attribute => $value){
                        if(empty($value)){
                            $_POST[$attribute] = null;
                        }
                    }
                    $page->setTitre($_POST['titre']);
                    $page->setDescription($_POST['description']);
                    $page->setSlug(Validator::sanitizeSlug($_POST['slug']));
                    $page->setContenu(Validator::sanitizeWysiwyg($_POST['contenu']));
                    $page->setStatut(1);
                    $page->setAuteurId($_SESSION['user']['id']);

                    // new
                    $page->setStatut($_POST['statut']);
                    if (!empty($_POST['personnage_id'])) {
                        $page->setPersonnageId($_POST[ 'personnage_id' ]);
                    }
                    if (!empty($_POST['chapitre_id'])) {
                        $page->setChapitreId($_POST[ 'chapitre_id' ]);
                    }
                    if (!empty($_POST['categorie_id'])) {
                        $page->setCategorieId($_POST[ 'categorie_id' ]);
                    }

                    if(!empty($_POST['media']['tmp_name'])){
                        if(!empty($page->getMediaId())){
                            MediaManager::deleteFile($page->getMediaId());
                        }
                        $page->setMediaId(MediaManager::saveFile($_POST['media_name'], $_POST['media'], $page));
                    }elseif(!empty($_POST['select_media'])){
                        $page->setMediaId($_POST['select_media']);
                    }

                    $page->save();
                    $page->commit();

                    header('Location:/page?page='.$page->getSlug());
                }catch (Exception $e) {
                    $page->rollback();
                    var_dump($e->getMessage());die;
                }
            }
        }else{
            $page = UrlHelper::getUrlParameters($_GET)['object'];
            $view = new View("page/updatePage");
            $view->assign("page", $page);
        }
    }

    public function getCountLikes()
    {
        $page = new PageModel();
        $page->setId($_GET['page_id']);
        echo $page->countLikes();
    }

    public function getCountUnlikes()
    {
        $page = new PageModel();
        $page->setId($_GET['page_id']);
        echo $page->countUnlikes();
    }

    public function delete()
    {
        $page = UrlHelper::getUrlParameters($_GET)['object'];
        $page->delete();
    }
}