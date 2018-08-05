<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$loader = new Twig_Loader_Filesystem('./template');
$twig = new Twig_Environment($loader, array('cache' => './tmp/cache', 'auto_reload' => true));

$di = new Controller\Database($host, $database, $username, $password);
$connection = $di->Connection(); 

$r = new Controller\Router($connection, $twig);
$r->route();
