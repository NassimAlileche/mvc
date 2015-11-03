<?php

namespace Library\Loader;

require_once(str_replace('Loader', 'Traits/Patterns/Singleton.php', __DIR__));

/**
 *  Classe permettant de charger automatiquement les classes appelées dans l'application 
 *  sans besoin d'utiliser include ou require
 * 	Notre classe devient un singleton grace à l'utilisation du Trait Singleton
 */
class Autoloader {

	use \Library\Traits\Patterns\Singleton;
	
	/**
	 * 	Chemin de base
	 * 	@var string
	 */
	private static $basePath = null;

	/**
	 * 	__construct()
	 *
	 * 	Constructeur par défaut de la classe
	 *
	 *	@return void
	 */
	private function __construct() {
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	/**
	 * setBasePath($path)
	 *
	 * Setter de $basePath
	 *
	 * @param   String  $path  [Chemin du projet (c:\wamp\www\...)]
	 *
	 * @return  void
	 * 
	 * @example 
	 *
	 *     $class::setBasePath("c:\wamp\www\recette")
	 */
	public static function setBasePath($path){
		self::$basePath = $path;
	}

	/**
	 * getBasePath()
	 *
	 * Getter de $basePath
	 *
	 * @return  String  [Retourne $basePath]
	 */
	public function getBasePath() { return self::$basePath; }

	/**
	 * autoload($class)
	 *
	 * Charge la classe
	 * 
	 * @param   String          $class  [Nom de la classe à charger]
	 *
	 * @return  void|Exception  [Retourne une exception si $basePath n'a pas été défini]
	 */
	protected static function autoload($class){
		if(is_null(self::$basePath)){
			throw new \Exception("basePath in" . __CLASS__ . " is Null");
		}
		$pathFile = self::$basePath . str_replace('\\', DIRECTORY_SEPARATOR, $class) . ".php";
		require_once($pathFile);
	}
}