<?php
function var_dump_pp($expression) { echo '<pre>'; var_dump($expression); echo '</pre>'; }
function print_r_pp($expression) { echo '<pre>'; print_r($expression); echo '</pre>'; }

if(!isset($_SESSION))
	session_start();

$basePath = str_replace("Public", "", dirname(__FILE__));

// Chargement de l'autoloader
require_once($basePath . 'Library/Loader/Autoloader.php');
$autoload = \Library\Loader\Autoloader::getInstance();
$autoload::setBasePath($basePath);

// Chargement de la config
$config = \Application\Configs\Settings::getInstance();
$config::setBaseUrl("http://localhost");
$config::readSettings();

// Connexion à la base de données
$db              = "test";
$connexionDBName = "c_test";
$host       = "localhost";
$user       = "root";
$userpasswd = "toor";

$DB = \Library\Model\Connexion::getInstance();
$DB::addConnexion($connexionDBName, $DB::connectDB($host, $db, $user, $userpasswd));

// Chargement du routeur
$router = \Library\Router\Router::getInstance();
$router::dispatchPage($_GET['page']);


//print_r_pp(array('SESSION' => $_SESSION, 'POST' => $_POST, 'GET' => $_GET));
