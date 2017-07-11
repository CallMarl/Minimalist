<?php

namespace App\Controllers;

use Core\Controller\Controller as CoreController;

class Controller extends CoreController{

    public function __construct(){
            $this->view_path = ROOT . '/app/views/';
            $this->template_path = ROOT . '/app/views/default.html.php';
    }

    public function home(){
        return parent::render('home.html.php');
    }

    public function error404(){
        return parent::render('error404.html.php');
    }
}
?>
