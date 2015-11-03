<?php

namespace Application\Controllers;

/**
 *
 */
class DefaultController extends \Library\Controller\Controller {
    

    function __construct() {
        parent::__construct();
        $this->setLayout("nolayout");
        $this->addStyleView("github.css");
        $this->addScriptView("github.js");
    }
    
    public function indexAction() {

    }

}