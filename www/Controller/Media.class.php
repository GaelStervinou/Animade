<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\View;
use App\Helpers\UrlHelper;
use App\Model\User;
use App\Model\Media as MediaModel;
use Exception;

class Media{

    public function listMedias()
    {
        if(Security::canAsAdmin() === true){
            $medias = (new MediaModel())->findManyBy([], ['statut', 'ASC']);
        }else{
            $medias = (new MediaModel())->findManyBy(['statut' => 2, 'user_id' => $_SESSION['user']['id']]);
        }
        $view = new View("media/listMedias");
        $view->assign("medias", $medias);
        $view->assign("meta",
            [
                'script' => ['../dist/js/dataTable.js'],
                'titre' => 'Liste des médias',

            ]);
    }

    public function updateStatut()
    {
        if(!empty($_GET)){
            $media = UrlHelper::getUrlParameters($_GET)['object'];
            try{
                $media->beginTransaction();
                if($media->getStatut() === 2) {
                    $media->setStatut(-1);
                }else{
                    $media->setStatut(2);
                }

                $media->save();
                $media->commit();

                header('Location:/media/listMedias');
            }catch (Exception $e) {
                $media->rollback();
                Security::returnError(403, $e->getMessage());
            }
        }else{
            Security::returnError(404);

        }
    }
}