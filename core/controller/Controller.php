<?php

namespace Core\Controller;

class Controller{

    protected $view_path;
    protected $template_path;

    public function __construct(){
            $this->view_path = ROOT . '\app\\';
            $this->template_path = NULL;
    }

    /*la fonction ob_start démarre le buffer, tout ce qui viendra avant la fonction ob_get_clean() s'ajoutera à la pile du buffer.
    ob_get_clean() Charge le contenu du buffer puis le supprime.*/
    public function render($view_name, $variable = []){
        ob_start();
        if(!empty($variable))
            extract($variable);

        if($view_name !== NULL){
            require($this->view_path . $view_name);
        }
        $content = ob_get_clean();
        require_once($this->template_path);
    }

    #retourne la template sans vu intégré
    public function noViewRender( $variable = []){
        $this->render(NULL, $variable);
    }
}
?>
