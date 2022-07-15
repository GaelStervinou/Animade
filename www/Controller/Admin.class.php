<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\Validator;
use App\Core\View;
use App\Model\Signalement as SignalementModel;
use App\Model\User as UserModel;
use App\Model\Page;

class Admin
{
    public function dashboard()
    {
        $view = new View("dashboard", "back");

        $user = Security::getUser();
        $view->assign("user", $user);

        $page = new Page();
        $pages = $page->findManyBy(['statut' => 2]);
        $view->assign("pages", $pages);

        $users = $user->findManyBy([
            'status' => [
                'operator' => '!=',
                'value' => -1
            ]
        ]);
        $view->assign("users", $users);

        $signalements = $this->getSignalementsCommentaireUnique();
        $view->assign("signalements", $signalements);
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
        $signalements = $this->getSignalementsCommentaireUnique();
        $view = new View("admin/listSignalements");
        $view->assign("signalements", $signalements);
    }

    public function getSignalementsCommentaireUnique()
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

        return $signalements;
    }

    public function administration(): void
    {

        if(!empty($_POST)){

            $user = new UserModel();

            $result = Validator::run($user->getSettingsForm(), $_POST);
            if(empty($result)){
                $configContent = "<?php\n";

                foreach($_POST as $key => $value){
                    $configContent .= "define(\"".$key."\", \"".$value."\");\n";
                }
                file_put_contents('conf.inc.php', $configContent);
            }

            header("Location:/admin/dashboard");
        }else{
            $view = new View("admin/manager", "back");
            $user = Security::getUser();
            $view->assign("user", $user);
        }

    }

    public static function getSettings()
    {
        $fileContent = file_get_contents('conf.inc.php');
        $settings = [];
        foreach (explode("\n", $fileContent) as $line) {
            preg_match('/(".{0,150}"), (".{0,150}")/', $line, $matches);
            if(!empty($matches)){
                $settings[str_replace('"', '', $matches[1])] = str_replace('"', '', $matches[2]);
            }
        }
        return $settings;


    }
}