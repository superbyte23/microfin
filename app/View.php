<?php

namespace App;

class View
{
    protected $view;
    protected $data = [];

    public function __construct($view, $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public static function render($view, $data = [])
    {
        $template = __DIR__ . '/Views/Layouts/Template.php';
        $content = __DIR__ . '/Views/' . $view . '.php';
        
        if (file_exists($content)) {
            extract($data);
            require_once $template;
            require_once $content;
        } else {
            echo "View not found: {$viewPath}";
        }
    }

    public static function pageError($view, $data = [])
    {
        $template = __DIR__ . '/Views/Layouts/Template.php';
        $content = __DIR__ . '/Views/' . $view . '.php';
        
        if (file_exists($content)) {
            extract($data);
            require_once $template;
            require_once $content;
        } else {
            echo "View not found: {$viewPath}";
        }
    }
}
