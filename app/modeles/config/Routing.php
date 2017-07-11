<?php

namespace App\Modeles\Config;

use Core\Router\Router;

class Routing extends Router{

    public function __construct($path, $pdo){
        parent::__construct($path, $pdo);
    }

    public function load($status = NULL){

        parent::set_request('GET');
        parent::set_controller('Controller');

        parent::set_action('home');
        parent::add_rule('/');
        parent::add_rule('home');

        parent::set_action('error404');
        parent::add_rule('/error404');
    }
}


?>
