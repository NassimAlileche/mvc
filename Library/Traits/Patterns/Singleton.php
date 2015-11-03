<?php

namespace Library\Traits\Patterns;

/**
 *  Ce trait implémente le patron de conception du singleton, un patron n'autorisant qu'une seule instance à la fois 
 *  de la classe qui l'utilise
 *
 *  @see http://php.net/manual/fr/language.oop5.traits.php
 */
trait Singleton {

	/**
	 * 	Instance statique de la classe
	 *
	 * 	@var Object
	 */
	private static $instance = null;

	/**
	 * 	getInstance()
	 *
	 * 	Récupère l'instance de la classe
	 *
	 * 	@return Object
	 */
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
}