<?php

namespace App\Controller;

use App\Core\CleanWords as Clean;
use App\Core\Validator;
use App\Core\View;
use App\Core\QueryBuilder;
use App\Core\Security;
use App\Model\User as UserModel;

use App\Model\Page as PageModel;

class Page{
    public function newPage()
    {
        if(!empty($_POST)){

        }else{
            $user = new UserModel();
            $user = $user->setId($_SESSION['user']['id']);
            $view = new View("newPage");
            $view->assign("firstname", $user->getFirstname());
            $view->assign("lastname", $user->getLastname());
        }
    }
}