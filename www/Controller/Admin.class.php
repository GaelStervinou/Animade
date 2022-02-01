<?php


namespace App\Controller;
use App\Core\View;

class Admin
{
    public function dashboard()
    {
        $firstname = "Gaël";
        $view = new View("dashboard", "back");
        $view->assign('firstname', $firstname);
    }
}