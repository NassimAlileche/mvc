<?php

namespace Library\Controller;

/**
 *  Classe abstraite permettant de construire un contrôleur 
 */
abstract class Controller implements iController {
    
    /**
    * URL de redirection
    * @var String
    */
    private $redirect = null;

    /**
    * Template de vue que l'on veut utiliser
    * @var String
    */
    private $layout = 'nolayout';

    /**
    * Type de document envoyé
    * @var String
    */
    private $responseHeader = 'text/html';
    
    /**
    * Pile de scripts à envoyer avec la vue
    * @var array String
    */
    private $scriptView = array();

    /**
    * Pile de styles à envoyer avec la vue
    * @var array String
    */
    private $styleView = array();
    
    /**
    * Variables utilisées dans les modules de la vue (parties de la page)
    * @var Associative Array
    */
    private $dataMod = array();

    /**
    * Variables utilisées dans la vue
    * @var Associative Array
    */
    private $dataView = array(
        "site_name"       => "PHP MVC...",
        "page_title"      => "Accueil",
        "message"         => "",
    );

    /**
     * Paramètres de requête additionnels
     * @var String Array 
     */
    //private $qparams = array();

    /**
     *  __construct()
     *
     *  Constructeur par défaut
     *
     *  @return void
     */
    function __construct() {

    }
    
    /**
     * setRedirect($url)
     *
     * Setter de $redirect
     *
     * @param   String  $url  [URL de redirection]
     * 
     * @return  void
     */
    protected function setRedirect($url) {
        $this->redirect = $url;
    }

    /**
     * setLayout($name)
     *
     * Setter de $layout
     *
     * @param   String   $name   [Nom du layout]
     * 
     * @return  Boolean  [Retourne true en cas de modification réussie, sinon false]
     */
    protected function setLayout($name) {
        $name = strtolower($name);
        if( !empty($name) && file_exists(APP_ROOT . "Views/Layouts/{$name}.phtml") ) {

            $this->layout = $name;

            // Scripts par défaut
            $this->addStyleView("bootstrap/bootstrap.min.css");
            $this->addStyleView("layouts/{$name}.css");
            $this->addStyleView("styles-common.css");

            $this->addScriptView("jquery/jquery.js");
            $this->addScriptView("bootstrap/bootstrap.js");

            return true;
        }
        return false;
    }
    
    /**
     * getLayout()
     *
     * Getter de $layout
     *
     * @return  String  [Nom du layout]
     */
    protected function getLayout() { return $this->layout; }
    
    /**
     * setResponseHeader($value)
     *
     * Setter de $responseHeader
     *
     * @param   String  $value  [Type de document à envoyer]
     * 
     * @return  Boolean [Retourne true en cas de modification réussie, sinon false]
     */
    protected function setResponseHeader($value) {
        $value          = strtolower($value);
        $possibilities  = array(
            "text"     => "text/plain",
            "html"     => "text/html",
            "css"      => "text/css",
            "js"       => "application/javascript",
            "json"     => "application/json",
            "xml"      => "application/xml"
        );
        if( array_key_exists($value, $possibilities) ) {
            $this->responseHeader = $possibilities[$value];
            return true;
        }
        return false;
    }

    /**
     * getResponseHeader()
     *
     * Getter de $responseHeader
     *
     * @return  String  [Type de document à envoyer]
     */
    protected function getResponseHeader() { return $this->responseHeader; }
    
    
    /**
     * setScriptView($script)
     *
     * Setter de $scriptView
     *
     * @param   String   $script  [Nom/Chemin du script à ajouter]
     * 
     * @return  Boolean  [Retourne true en cas de modification réussie, sinon false]
     */
    protected function setScriptView($script) {
        if( !is_string($script) || empty($script) ) {
            return false;
        }
        foreach($this->scriptView as $s) {
            if($s === $script)
                return false;
        }
        array_push($this->scriptView, $script);
        return true;
    }
    protected function addScriptView($script) {
        return $this->setScriptView($script);
    }

    /**
     * getScriptView()
     *
     * Getter de $scriptView
     *
     * @return  Array  [Array des scripts à charger dans la view]
     */
    protected function getScriptView() { return $this->scriptView; }
    
    /**
     * setStyleView($style)
     *
     * Setter de $styleView
     *
     * @param   String   $style  [Nom/Chemin du style à ajouter]
     * 
     * @return  Boolean  [Retourne true en cas de modification réussie, sinon false]
     */
    protected function setStyleView($style) {
        
        if( !is_string($style) || empty($style) ) {
            return false;
        }
        foreach($this->styleView as $s) {
            if($s === $style)
                return false;
        }
        array_push($this->styleView, $style);
        return true;
    }
    protected function addStyleView($style) {
        return $this->setStyleView($style);
    }

    /**
     * getStyleView()
     *
     * Getter de $styleView
     *
     * @return  Array  [Array des styles à charger dans la vue]
     */
    protected function getStyleView() { return $this->styleView; }

    /**
     * setDataView($data)
     *
     * Setter de $dataView
     * Fusionne (merge) les nouvelles données aux données déjà existantes
     * Ces données sont accessibles depuis les vues
     *
     * @param   Array  $data  [Array associatif "key" => "value"]
     *
     * @return  void
     */
    protected function setDataView($data) {
        $this->dataView = array_merge($this->dataView, $data);
    }

    /**
     * getDataView
     *
     * Getter de $dataView
     *
     * @return  Array  [Liste des variables accessibles depuis la vue]
     */
    protected function getDataView() { return $this->dataView; }
    
    /**
     * setDataMod($data)
     *
     * Setter de $dataMod
     * Fusionne (merge) les nouvelles données aux données déjà existantes
     * Ces données sont accessibles depuis les vues
     *
     * @param   Array  $data  [Array associatif "key" => "value"]
     *
     * @return  void
     */
    protected function setDataMod($data) {
        $this->dataMod = array_merge($this->dataMod, $data);
    }
    /**
     * getDataMod()
     *
     * Getter de $dataMod
     *
     * @return  Array  [Liste des variables accessibles depuis la vue]
     */
    protected function getDataMod() { return $this->dataMod; }
    
    /**
     * addFilesRender
     * 
     * Méthode permettant d'inclure les styles et les scripts contenus dans les array $script et $style
     * dans le fichier html donné en paramètre
     *
     * @param   String  &$html  [Pointeur sur le template de la vue]   
     *
     * @return  void
     */
    private function addFilesRender(&$html) {
        foreach( $this->scriptView as $s) {
            $html = str_replace('</body>', "\t<script type=\"text/javascript\" src=\"" . WEB_ROOT . "js/$s\"></script>\n</body>", $html);
        }
        foreach( $this->styleView as $s) {
            $html = str_replace('</head>', "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . WEB_ROOT . "css/$s\">\n</head>", $html);
        }
    }
    
    /**
     * renderView($controller, $action, $echo=true)
     * 
     * Cette méthode est appelée dans router::dispatchPages
     * Cette méthode ne doit pas être appelée par l'utilisateur
     *
     * @param   String        $controller [description]
     * @param   String        $action     [description]
     * @param   Boolean       $echo       [description]
     *
     * @return  String|void   [Retourne la vue complète]
     */
    public function renderView($controller, $action, $echo=true) {
        global $router;
        
        if(!is_null($this->redirect)) {
            header("location: {$this->redirect}");
            die();
        }
        
        /* Déterminer le chemin vers la vue */
        $pathView = APP_ROOT."Views/Controllers/".
            str_replace("Application\Controllers\\", "", $controller)."/".
            str_replace("Action", "", $action).".phtml";
                
        if(file_exists($pathView)) {
            header("Content-type: ".$this->getResponseHeader()."; charset=utf-8");
            // extract permet de créer et d'initialiser les variables annoncées dans le tableau associatif dataView
            // c'est une manière plus rapide de définir des variables que la fonction define($varName, $value)
            extract($this->getDataView());
            
            // stockage de la vue (morceau principal de la page)
            ob_start();
                include_once($pathView);
            $content_view = ob_get_clean();
            // utilisation de la vue
            // $content_view doit se trouver dans chaque layout
            ob_start();
                include_once(APP_ROOT."Views/Layouts/".$this->getLayout().".phtml");
            $finalRender = ob_get_clean();
            
            // ajout des styles et des scripts javascript et rendu final de la page dans la variable $finalRender
            $this->addFilesRender($finalRender);
            
            if($echo)
                echo($finalRender);
            else
                return $finalRender;
        }
        elseif( !empty($_SERVER["HTTP_REFERER"]) ) {
            header("location: ".$_SERVER["HTTP_REFERER"]);
            die();
        }
        else {
            header("location: ".LINK_ROOT);
            die();
        }
    }
    
    /**
     * renderModule($module, $action)
     * 
     * Cette méthode est appelée dans router::dispatchPages
     * Cette méthode ne doit pas être appelée par l'utilisateur
     *
     * @param   String  $module  [description]
     * @param   String  $action  [description]     
     *
     * @return  void|Exception   [Retourne une exception si le fichier correspondant au module demandé n'a pas été trouvé]
     */
    public function renderModule($module, $action) {
        //var_dump("Dans le module $module avec l'action $action");
        // Déterminer le chemin vers la vue
        $pathView = APP_ROOT."Views/Modules/".
                    str_replace("Application\Modules\\", "", $module)."/".
                    str_replace("Action", "", $action).".phtml";
        
        if(file_exists($pathView)) {
            extract($this->getDataMod());
            include_once($pathView);
        }
        else {
            throw new \Exception("Erreur pour le module {$module} avec l'action {$action} : le fichier " . $pathView . " n'existe pas.");
        }
    }
}