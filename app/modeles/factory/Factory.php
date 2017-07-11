<?php

namespace App\Modele\Factory;

use Core\Database\Statement;

use App\App;
use App\Modele\Entity\TableName;

Class Factory{

    protected static $bdd;

    protected function __construct(){
        $this->pdo = App::loadDatabase();
    }
}

?>
