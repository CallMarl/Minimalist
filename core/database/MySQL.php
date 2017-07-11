<?php

namespace Core\Database;

use \PDO;

class MySQL{

    private static $instance;
    private $server_setting = [];
    private $database;

    /*$keys(db_name, db_host, db_user, db_pass);*/
    private function get($key){
        return (isset($this->server_setting[$key])) ? $this->server_setting[$key] : new \Exception('Unknow server identifiant');
    }

    private function connect(){
        try{
            $database = new PDO('mysql:dbname='.$this->get('db_name').';host='.$this->get('db_host'),
                                 $this->get('db_user'),
                                 $this->get('db_pass'),
                                 array(PDO::ATTR_PERSISTENT => TRUE,
                                       PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
                                );
            $database->exec("SET CHARACTER SET utf8");
        }catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        $this->database = $database;
    }

    /*Cette fonction est déclaré private car l'on préférera une instanciation unique, voir sinInstance (single instance).*/
    private function __construct($file){
        $this->server_setting = require($file);
        $this->connect();
    }

    /*Cette fonction permet d'intancier la class (c'est son constructeur public), elle va chercher les donnée dans un fichier composé d'un unique tableau*/
    protected static function sinInstance($file){
        return (self::$instance == NULL) ? self::$instance = new MySQL($file) : self::$instance;
    }

    protected function getDatabase(){
        return $this->database;
    }
}

?>
