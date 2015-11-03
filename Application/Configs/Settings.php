<?php

namespace Application\Configs;

/**
 *  Classe définissant les constantes utilisées dans l'application
 *  Notre classe devient un singleton grace à l'utilisation du Trait Singleton
 */
class Settings {

	use \Library\Traits\Patterns\Singleton;

	/**
	 * 	URL de base
	 *	@var string
	 */
	private static $baseUrl = null;

	/**
	 * 	__construct()
	 *
	 *	Constructeur par défaut
	 *
	 * 	@return void 	
	 */
	private function __construct(){
			
	}

	/**
	 * setBaseUrl($url)
	 *
	 * Setter de $baseUrl
	 *
	 * @param   String  $url  [URL de base]
	 *
	 * @return  void
	 */
	public static function setBaseUrl($url){
		self::$baseUrl = $url;
	}

	/**
	 * readSettings()
	 *
	 * Configuration des liens
	 *
	 * @return  void|Exception  [Retourne une exception si $baseUrl n'a pas été défini]
	 */
	public static function readSettings(){

		if(is_null(self::$baseUrl)){
			throw new \Exception("baseUrl in" . __CLASS__ . " is Null");
		}
		define('WEB_ROOT', 	  str_replace('index.php',        '',               $_SERVER["SCRIPT_NAME"]));
		define('LINK_ROOT',   str_replace('Public/index.php', '',               self::$baseUrl . $_SERVER["SCRIPT_NAME"]));
		define('APP_ROOT', 	  str_replace('Public/index.php', 'Application/',   $_SERVER["SCRIPT_FILENAME"]));
		define('LIB_ROOT', 	  str_replace('Public/index.php', 'Library/',       $_SERVER["SCRIPT_FILENAME"]));
		define('PUB_ROOT', 	  str_replace('Public/index.php', 'Public/',        $_SERVER["SCRIPT_FILENAME"]));
		define('IMG_ROOT',    str_replace('Public/index.php', 'Public/images/', $_SERVER["SCRIPT_FILENAME"]));

		define('SALT_PASSWORD', 'X_ ##8[+VN7hWcmeOhHzbhaP$_I|C{-7=8Oy$W^VH(?}bRGndcM{%2r]}d?NH]6N');
		
		define('WEBSERVICE_ROOT', str_replace('Public/', 'Webservice/', WEB_ROOT));
	}
}