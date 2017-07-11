<?php

namespace Core\Router;

use Core\Router\Path;

define('REQUEST', 'request');
define('CONTROLLER', 'controller');
define('ACTION', 'action');

abstract class Router{

        const REQUEST = 'request';
        const CONTROLLER = 'controller';
        const ACTION = 'action';

        protected static  $instance;
        protected $path;

        protected $rules = [];
        protected $param = [];
        protected $request = [];
        protected $controller;
        protected $action;

        protected $pdo;

        public function __construct($path = "App\\Controller", $pdo = NULL){
            $this->path = $path;
            $this->pdo = $pdo;
        }

        protected function set_request($request, $bool = TRUE){
            if($bool) unset($this->request);
            $this->request[] = $request;
        }

        protected function set_controller($controller){
            unset($this->controller);
            $this->controller = $controller;
        }

        protected function set_action($action){
            unset($this->action);
            $this->action = $action;
        }

        protected function add_rule($url){
            Path::sanitalize($url);
            $this->rules[$url] = [REQUEST => $this->request,
                                  CONTROLLER => $this->controller,
                                  ACTION => $this->action];
        }

        public function run(){

            $url = urldecode($_SERVER['REQUEST_URI']);
            Path::sanitalize($url);

            $param = strstr($url,'?');

            if($param !== FALSE){
                $url = strstr($url, '?', TRUE);

                $param = substr($param, 1, -1) . substr($param, -1);
                $param = explode('&', $param);

                foreach($param as $value){
                    if(strstr($value, '=', TRUE) === FALSE) $params[':' . $value] = NULL;
                    else $params[':' . strstr($value, '=', TRUE)] = substr(strstr($value, '=', FALSE), 1);
                }
                $param = $params;
                unset($params);
            }

            $tmp = strlen($url);
            if($tmp < 2) $tmp = 2;

            $suburl = function () use ($url){
                return $url;
            };

            foreach (array_keys($this->rules) as $rulekey){
                $subkey = substr($rulekey, 0, $tmp);

                if(strcmp($url, $subkey) === 0){
                    if($param !== FALSE){
                        preg_match_all('/^[^\[]+|(?<=\]).[^\[]+/', $rulekey, $matches);
                        $subkey = implode('', $matches[0]);
                        $url = Path::path_linker($url, implode('\\', array_keys($param)));
                    }

                    if(strcmp($url, $subkey) === 0){
                        $rule = $this->rules[$rulekey];
                        break;
                    }
                    else if($param !== FALSE ) $url = $suburl();
                }
            }

            if(isset($rule)){
                if($param !== FALSE){
                    $params = array_values($param);
                    preg_match_all('/(?<=\\:)[^\\\]*/', $rulekey, $matches);
                    foreach ($matches[0] as $key => $value) {
                        preg_match('/(?<=\[)[^\]]*/', $value, $match);

                        (!empty($match)) ? $matches[0][$key] = $match[0] : $matches[0][$key] = NULL;
                    }

                    if(!empty($matches[0])){
                        foreach ($matches[0] as $key => $value) {
                            $tmp = explode('|', $value);
                            if($params[$key] !== NULL) $tablist[$tmp[0]][$tmp[1]] = $params[$key];
                        }

                        foreach ($tablist as $tab => $value) {
                            $colval = array();
                                for($i = 0; $i < count($value); $i++){
                                    $key = key($value);
                                    $colval[] =  $key . "='" . $value[$key] . "'";
                                    next($value);
                                }
                            $colval_str = implode(' AND ', $colval);

                            $statement = $this->pdo->prepare("SELECT * FROM {$tab} WHERE {$colval_str}");
                            $statement->execute();

                            if($statement->fetch() === FALSE){
                                return FALSE; #wrong parameters
                            }
                        }
                    }
                }

                if(in_array($_SERVER['REQUEST_METHOD'], $rule[REQUEST])){
                    $controller = Path::path_linker($this->path, $rule[CONTROLLER], FALSE);
                    $controller = new $controller();

                    $action = $rule[ACTION];
                    $controller->$action();
                    return TRUE;
                }else return FALSE; #bad request methode 400
            }
            return FALSE; #bad uri request 404
        }

        abstract function load($status);
}

?>
