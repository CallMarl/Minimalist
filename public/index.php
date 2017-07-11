<?php

ini_set('display_errors', 1);

require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . '__const.php');
require_once(ROOT . DS . 'app' . DS . 'App.php');

$app = new App\App();
$app::loadAutoloader();

session_start();

$app::loadRouting();

?>
