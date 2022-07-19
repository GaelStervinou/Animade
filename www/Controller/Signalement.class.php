<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\Security;
use App\Core\Validator;
use App\Core\BaseSQL;
use App\Model\Signalement as SignalementModel;

class Signalement{
    public function signaler()
    {
        if(!empty($_POST)){
            try {
                $signalement = new SignalementModel();
                $signalement->beginTransaction();
                $signalement->setUserId(Security::getUser()->getId());
                $canComment = Validator::canSignalComment($_POST['commentaire_id']);
                if($canComment === true){
                    $signalement->setCommentaireId($_POST[ 'commentaire_id' ]);
                }else{
                    Security::returnError(403, $canComment);
                }
                $signalement->setStatut(2);
                $signalement->save();
                $signalement->commit();

                echo "Commentaire signalé";
                return true;

            }catch (Exception $e) {
                $signalement->rollback();
                Security::returnError(500);
            }
        }
        return Security::return403("Page innaccessible");
    }
}