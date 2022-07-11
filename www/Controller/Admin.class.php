<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Signalement as SignalementModel;
use App\Model\User as UserModel;

class Admin
{
    public function dashboard()
    {
        $firstname = "Yves";
        $lastname = "SKRZYPCZYK";

        $view = new View("dashboard", "back");
        $view->assign("firstname", $firstname);
        $view->assign("lastname", $lastname);
    }

    public function listUsers()
    {
        $user = new UserModel();
        $users = $user->findManyBy([]);
        $view = new View("admin/listUsers", "back");
        $view->assign("users", $users);
    }

    public function listSignalements()
    {
        $signalementRequest = new SignalementModel();
        $signalements = $signalementRequest->findManyBy(['statut' => 2], ['date_creation', 'DESC']);

        $listCommentaires = [];
        foreach ($signalements as $key => $signalement) {
            $commentaire_id = $signalement->getCommentaireId();
            if ( $commentaire_id !== null && !in_array($commentaire_id, $listCommentaires)){
                $listCommentaires[] = $commentaire_id;
            }else{
                unset($signalements[$key]);
            }
        }

        $view = new View("admin/listSignalements");
        $view->assign("signalements", $signalements);
    }
}