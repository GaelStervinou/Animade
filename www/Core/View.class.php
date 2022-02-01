<?php

namespace App\Core;

class View
{
    private $view;
    private $template;
    private $data;

    public function __construct($view, $template = "front")
    {
        $this->setView($view);
        $this->setTemplate($template);
    }

    public function setView($view){
        $this->view = strtolower($view);
    }

    public function setTemplate($template){
        $this->template = strtolower($template);
    }

    public function __toString()
    {
        return "test classe view";
    }

    public function __destruct()
    {
        extract($this->data);
        include "View/".$this->template.".tpl.php";
    }

    public function assign($key, $value):void
    {
        $this->data[$key] = $value;
    }

}