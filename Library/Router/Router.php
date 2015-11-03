<?php

namespace Library\Router;

require_once(str_replace('Router', 'Traits/Patterns/Singleton.php', __DIR__));

/**
 *  Classe routant les urls de l'application en faisant correspondre les urls à des contrôleurs 
 *  Cette classe devient un singleton grace à l'utilisation du Trait Singleton
 */
class Router {

    use \Library\Traits\Patterns\Singleton;
    
    /**
     * Constructeur par défaut
     *
     */
    private function __construct() {

    }
    
    /**
     * getControllerPath($name, $strict=true)
     *
     * Génère le chemin vers un contrôleur
     *
     * @param   String   $name
     * @param   Boolean  $strict
     *
     * @return  String
     */
    static private function getControllerPath($name, $strict=true) {
        $basefile = ($strict)? ucfirst( strtolower($name) ) : $name;
        return APP_ROOT . 'Controllers' . DIRECTORY_SEPARATOR . $basefile . '.php';
    }

    /**
     * getControllerClassName($name, $strict=true)
     *
     * Génère le nom complet de la classe d'un contrôleur
     *
     * @param   String   $name
     * @param   Boolean  $strict
     *
     * @return  String
     */
    static private function getControllerClassName($name, $strict=true) {
        $class = ($strict)? ucfirst( strtolower($name) ) : $name;
        return "\Application\Controllers\\" . $class;
    }
    
    /**
     * getModulePath($name, $strict=true)
     *
     * Génère le chemin vers un module
     *
     * @param   String   $name
     * @param   Boolean  $strict
     *
     * @return  String
     */
    static private function getModulePath($name, $strict=true) {
        $basefile = ($strict)? ucfirst( strtolower($name) ) : $name;
        return APP_ROOT . 'Modules' . DIRECTORY_SEPARATOR . $basefile . '.php';
    }
    
    /**
     * getModuleClassName($name, $strict=true)
     *
     * Génère le nom complet de la classe d'un module
     *
     * @param   String   $name
     * @param   Boolean  $strict
     *
     * @return  String
     */
    static private function getModuleClassName($name, $strict=true) {
        $class = ($strict)? ucfirst( strtolower($name) ) : $name;
        return "\Application\Modules\\" . $class;
    }
    
    /**
     * getActionName($name, $strict=true)
     *
     * Génère le nom d'une action
     *
     * @param   String   $name
     * @param   Boolean  $strict
     *
     * @return  String
     */
    static private function getActionName($name, $strict=true) {
        $action = ($strict)? strtolower($name) : $name;        
        return $action . "Action";
    }

    /**
     * dispatchPage($page, $strict=true)
     *
     * Traitement et transmission par le routeur de la page indiquée en paramètre 
     *
     * @param   String   $name
     * @param   Boolean  $strict
     *
     * @return  void
     */
    static public function dispatchPage($page, $get=array(), $strict=true) {
        $query = explode('/', $page);
        // Défauts
        $controller = self::getControllerClassName('DefaultController', false);
        $action     = self::getActionName('index', false);

        // Si la requête n'est pas /, on doit retrouver le contrôleur et l'action correspondants à la requête

        if(!empty($page[0])) {

            // Contrôleur
            if( file_exists(self::getControllerPath($page[0])) && class_exists(self::getControllerClassName($page[0])) ) {
                $controller = self::getControllerClassName($page[0]);
                array_splice($page, 0, 1);
            }
            else {
                $controller = self::getControllerClassName('Error');
            }

            // Méthode
            if( !empty($page[0]) && method_exists($controller, self::getActionName($page[0], false)) ) {
                $action = self::getActionName($page[0], false);
                array_splice($page, 0, 1);
            }
        }

        $controller = new $controller;
        //var_dump($controller, $action);


        call_user_func_array(array($controller, $action), array());
        call_user_func_array(
            array($controller, 'renderView'), 
            array(
                "controller" => get_class($controller),
                "action"     => $action,
                "echo"       => true 
            ) 
        );
        
        unset($controller, $action);
    }
    
    /**
     * dispatchModule($module, $action, array $params=array() )
     *
     * Traitement et transmission par le routeur du module pour l'action indiquée en paramètre 
     *
     * @param   String  $module
     * @param   String  $action
     * @param   Array   $params
     *
     * @return  void|Exception
     */
    static public function dispatchModule($module, $action, array $params=array() ) {
        
        if(empty($module)) {
            throw new \Exception("Error parameter module name is Required for dispatchModule method.");
        }
        
        if(empty($action)) {
            throw new \Exception("Error parameter action name is Required for dispatchModule method.");
        }

        if( file_exists( self::getModulePath($module)) && class_exists(self::getModuleClassName($module)) ) {
            $module = self::getModuleClassName($module);
            $module = new $module;
            
            if(method_exists($module, self::getActionName($action))) {
                $action = self::getActionName($action);
                
                call_user_func_array( array($module, $action), $params);
                call_user_func_array(
                    array($module, 'renderModule'), 
                    array(
                        "module" => get_class($module),
                        "action" => $action
                    )
                );
                unset($action);
            }
            else {
                throw new \Exception("Error Module Action : '$action' not found.");
            }
            
            unset($module);
        }
        else {
            throw new \Exception("Error Module : '$module' not found.");
        }
    }
}