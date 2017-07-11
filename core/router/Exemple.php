<?php

namespace Core\Router;

class Demo extends Router{

    public function __construct($path, $pdo){
        #$path chemin vers le dossier des controllers;
        #$pdo base de donné connecté;
        parent::__construct($path, $pdo);
    }

    public function load($status = NULL){

        parent::set_request('GET'); #les regle ci-dessou acceptent les methodes de requete de type GET

        #placer les erreur en premier
        #----------------------------
        parent::set_controller('Error'); #Nom du controller = $path . Error.php
        parent::set_action('Error404'); #Le nom de la fonction du controller Error.php qui gèra la règle;
        parent::add_rule('/404'); #Nom de la règle correspond à l'URI
                                  #elle englobe tout les paramètre précédents qui ne sont par recouverts

        parent::set_action('Error400'); #Le nom de la fonction du controller Error.php qui gèra la règle;
        parent::add_rule('/400');#Méthode de requete = GET, Controller = Error.php, Action = Error400;

        parent::set_controller('Simple'); #Changement de controller;
        parent::set_action('home');#nom fonction de controller
        parent::add_rule('/'); #Méthode de requete = GET, Controller = Simple.php, Action = home;
        parent::add_rule('/home'); #Méthode de requete = GET, Controller = Simple.php, Action = home;

        parent::set_request('POST');#Méthode de requete = POST.
        parent::set_request('GET', TRUE);#Méthode de requete = POST et GET.

        parent::set_action('blog');
        parent::add_rule('blog/:page');# les : signifi un paramètre par exemple www.monsite.com/blog?page=1
        parent::add_rule('blog/:category/:id'); #www.monsite.com/blog?category=tuto&id=1
        #Même règle avec des controlle d'existance en bdd.
        parent::add_rule('blog/:category/:id[tab/col]'); #ici table vaut le nom de la table en bdd et col le nom de la colone
                                                         #par exemple table blog et colone id;

        if($status === "value"){ #pour des règle spécifique à certaine personnes;

        }
    }
}


?>
