<?php

namespace Core\Database;

use \PDO;
use Core\Database\MySQL;

class Statement extends MySQL{

    public $pdo;
    private $class_name;
    private $table_name;
    private $selector = array();


    public function __construct($server_setting){
        $this->pdo = parent::sinInstance($server_setting)->getDatabase();
        return $this;
    }

    public function mine(){
        return $this->pdo;
    }

    public function set_selector($selector){
        if(!is_array($selector)){
            $this->selector[] = $selector;
        }
        else{
            $this->selector = array_merge($this->selector, $selector);
        }
    }

    public function set_classname($class_name){
        $this->class_name = $class_name;

        $table_name = preg_replace('/Entity/', '', $class_name);
        $table_name = explode('\\', $table_name);
        $this->table_name = lcfirst(end($table_name));
    }

    public function unset_selector(){
        if(isset($this->selector)){
            unset($this->selector);
            $this->selector = array();
        }
    }

    public function creat($information = []){
        foreach ($information as $key => $value){
            if($value === NULL){
                unset($information[$key]);
            }
            $key = $key . ' = ? ';
        }
        $columns = '`' . implode('` , `', array_keys($information)). '`';
        $values = "'" . implode("' , '", array_values($information)) . "'";

        $statement = $this->pdo->prepare("INSERT INTO {$this->table_name} ({$columns})  VALUES ({$values})");
        $statement->execute();
    }

    private function get_selector($information){
        $selector = '';
        foreach($this->selector as $value){
            $selector[] = $value . '= \'' . $information[$value] . '\'';
        }
        return implode(' AND ', $selector);
    }

    public function select($information = []){
        $selector = $this->get_selector($information);

        $statement = $this->pdo->prepare("SELECT * FROM {$this->table_name} WHERE {$selector}");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, $this->class_name);
        return $statement;
    }

    public function update($information = []){
        $string = '';
        foreach ($information as $key => $value) {
            if($value=== NULL){
                unset($information[$key]);
            }
            else{
                $value = str_replace('\'', '\\\'', $value);
                $string[] = '' . $key . '=\'' . $value . '\'';
            }
        }
        $string = implode(' , ', $string);

        $selector = $this->get_selector($information);

        $statement = $this->pdo->prepare("UPDATE {$this->table_name} SET {$string} WHERE {$selector}");
        $statement->execute();
    }

    public function delete($information = []){
        $selector = $this->get_selector($information);
        $statement = $this->pdo->prepare("DELETE FROM {$this->table_name} WHERE {$selector} ");
        $statement->execute();
    }
}

?>
