<?php

#backslash is important to spécified you want a native class
use \PDO;

class Connexion{

    private static $instance;
    private $server_setting = [];
    private $database;

    #use singleton to instanciate
    private function __construct($file){
        $this->file = require($file);
        $this->connect();
    }

    private function get($key){
        return (isset($this->file[$key])) ? $this->file[$key] : new \Exception('Unknow server identifiant');
    }

    public function connect(){
        try{
            $database = new PDO('mysql:dbname='.$this->get('db_name').';host='.$this->get('db_host'),
                                 $this->get('db_user'),
                                 $this->get('db_pass'),
                                 array(PDO::ATTR_PERSISTENT => TRUE,
                                       PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            #For accent like : éêèïçõ...
            $database->exec("SET CHARACTER SET utf8");
        }catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        $this->database = $database;
    }

    protected function sinInstance($file){
        return (self::$instance == NULL) ? self::$instance = new MySQL($file) : self::$instance;
    }

    protected function getDatabase(){
        return $this->database
    }
}

?>
<?php

use Connexion;

class Statement extends Connexion{

    private $pdo;

    public function __construct($file){
        $this->pdo = parent::sinInstance($file)->getDatabase();
        return $this;
    }

    public function mine(){
        return $this->pdo;
    }

    #You can also put general function here like:
        public function creat(){}
        public function select(){}
        public function update(){}
        public function delete(){}

    #thinking about entity and function get_object_vars();
}

?>

<?php
# File to config.
return array(
    "db_name" => "mydb",
    "db_host" => "localhost",
    "db_user" => "root",
    "db_pass" => ""
);

?>

<?php

const SERVER_SETTING = 'root/to/my/server-setting/file'
use Statement;

$pdo = new Statement(SERVER_SETTING)->mine();

    $statement = $pdo->prepare('SELECT * FROM mytab');
    $statement->execute();
    $statement->fecthAll();

#or

$pdo = new Statement(SERVER_SETTING);

    $statement = $pdo->mine->prepare('SELECT * FROM mytab');
    $statement->execute();
    #for entity: statement->setFetchMode();
    $statement->fetchAll();

?>
