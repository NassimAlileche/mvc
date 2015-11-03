<?php

namespace Library\Model;

/**
 *  Classe permettant d'établir une connexion avec une base de données MySQL
 *  Cette classe devient un singleton grace à l'utilisation du Trait Singleton
 */
class Connexion {
    
    use \Library\Traits\Patterns\Singleton;
    
    /**
     * Tableau associatif d'objets PDO
     * Chaque objet représente une connexion MySQL
     *
     * @var Array Object/Class \PDO
     */
    public static $listConnexions = array();

    /**
     * Constructeur par défaut
     *
     */
    private function __construct() {
        
    }

    /**
     * getConnexion($name)
     *
     * Obtenir la connexion en fonction de $name
     *
     * @param   String  $name     [Nom de la connexion]
     *
     * @return  String|Exception  [Connexion correspondante à $name ou Exception]
     */
    public static function getConnexion($name) {
        if( !array_key_exists($name, self::$listConnexions)) {
            throw new \Exception("Connexion name : '$name' not found");
        }
        return self::$listConnexions[$name];
    }
    
    /**
     * méthode getListConnexionsName
     *
     * Obtenir la liste des connexions enregistrées
     *
     * @param   void
     *
     * @return  Array  [Liste des connexions]
     */
    public static function getListConnexionsName() { return array(self::$listConnexions); }
    
    /**
     * connectDB($host, $dbname, $user, $password, $charset='UTF8')
     *
     * Connexion à la base de données MySQL via PDO
     *
     * @param   String  $host      [Adresse du serveur de base de données]
     * @param   String  $dbname    [Nom de la base de données]
     * @param   String  $user      [Utilisateur MySQL]
     * @param   String  $password  [Mot de passe de l'utilisateur MySQL]
     * @param   String  $charset   [Charset de connexion]
     *
     * @return  Object PDO
     */
    public static function connectDB($host, $dbname, $user, $password, $charset='UTF8') {
        $database = new \PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $database->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $database->exec("SET CHARSET $charset");
        
        return $database;
    }
    
    /**
     * addConnexion($connexionName, $objectPDO)
     *
     * Ajoute une nouvelle connexion à la liste des connexions
     *
     * @param   String      $connexionName  [Nom de la connexion]
     * @param   PDO Object  $objectPDO      [Nom de l'objet PDO]
     *
     * @return  void
     */
    public static function addConnexion($connexionName, $objectPDO) {
        self::$listConnexions[$connexionName] = $objectPDO;
    }    
}