<?php

namespace App\Modeles\Entity;

class UserEntity{

    private $id;
    private $pseudo;
    private $password;
    private $email;
    private $level;

    public function get_vars(){
        return get_object_vars($this);
    }

    public function get_className(){
        return __CLASS__;
    }

    public function set_id($id){
        $this->id = $id;
    }

    public function set_pseudo($pseudo){
        $this->pseudo = $pseudo;
    }

    public function set_password($password){
        $this->password = $password;
    }

    public function set_email($email){
        $this->email = $email;
    }

    public function set_level($level){
        $this->level = $level;
    }


    public function get_id(){
        return $this->id;
    }

    public function get_pseudo(){
        return $this->pseudo;
    }

    public function get_password(){
        return $this->password;
    }

    public function get_email(){
        return $this->email;
    }

    public function get_level(){
        return $this->level;
    }
}
?>
