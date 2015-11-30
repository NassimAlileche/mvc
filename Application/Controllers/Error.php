<?php

namespace Application\Controllers;

/**
 *
 */
class Error extends \Library\Controller\Controller {
    

    function __construct() {
        parent::__construct();
        $this->setLayout("nolayout");
        //$this->addStyleView("");
        //$this->addScriptView("");
    }
    
    public function indexAction() {
        $this->setDataView( array("page_title" => "Erreur, la page que vous avez demandé n'a pas été trouvée...") );
        header("HTTP/1.0 404 Not Found");
    }

}