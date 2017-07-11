<?php

namespace App;

use Core\Database\Statement;

use App\Modeles\Config\Routing;

class App{

    private static $instance;

    public static function sinInstance(){
        return (self::$instance == NULL) ? self::$instance = new App() : self::$instance;
    }

    public static function loadAutoloader(){
        require_once(ROOT . '\app\Autoloader.php');
        Autoloader::register();
    }

    public static function loadDatabase(){
        return new Statement(HOST);
    }

    public static function loadRouting(){
       $routing = new Routing("App/Controllers", self::loadDatabase()->mine());
       $routing->load();
       if(!$routing->run()){
           header('location: /error404');
       }
    }
}
?>
