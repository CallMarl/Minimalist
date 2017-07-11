<?php

namespace App;

class Autoloader{

    /*__CLASS__ donne le nom de la class courante.
    spl_autoload_register prend en paramètre une fonction, avec une méthode appelé callable (fonctionnalité de rappel:
    https://secure.php.net/manual/fr/language.types.callable.php) ici la fonction autoload() de la class Autoloader */
    public static function register(){
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /*__NAMESPACE__ donne le namespace que la class courante.
     ici on demande d'enlever le namespace de la class appeler: $class_name, avec la fonction str_replace */
    private static function autoload($class_name){
        var_dump($class_name);
        $class = str_replace(__NAMESPACE__, '', $class_name);
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . $class_name . '.php');
    }

}

?>
