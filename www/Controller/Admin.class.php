<?php

namespace App\Controller;

use App\Core\View;
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
}