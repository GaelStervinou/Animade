<?php

namespace App\Core;

use App\Core\Security;

class View
{
    private $view;
    private $template;
    private $data = [];

    public function __construct($view, $template = "front")
    {
        $this->setView($view);
        if($template === "without"){
            $this->setTemplate($template);
        }else{
            $user = Security::getUser();
            if($user !== false && $user->getRoleId() >= 3){
                $this->setTemplate("back");
            } else {
                $this->setTemplate("front");
            }
        }
    }

    public function setView($view){
        $this->view = strtolower($view);
    }

    public function setTemplate($template){
        $this->template = strtolower($template);
    }

    public function assign($key, $value):void
    {
        $this->data[$key] = $value;
    }

    public function includePartial($name, $config)
    {
        if(!file_exists('View/partial/'.$name.'.partial.php'))
        {
            die('partial : '. $name . ' 404' );
        }
        include('View/partial/'.$name.'.partial.php');
    }

    public function includeView($name)
    {
        if(!file_exists('View/'.$name.'.view.php'))
        {
            die('partial : '. $name . ' 404' );
        }
        include('View/'.$name.'.view.php');
    }

    public function __toString():string
    {
        return "Ceci est la classe View";
    }


    public function __destruct()
    {
        //Array ( [firstname] => Yves )
        extract($this->data);
        include "View/".$this->template.".tpl.php";
    }

}