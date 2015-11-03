<?php

namespace Library\Message;

/**
 *  Classe permettant de gérer les messages de debug en session
 */
class Message {

    /**
     * __construct()
     *
     * Constructeur par défaut
     *
     * @return  void
     */
    public function __construct() {
        if(!isset($_SESSION)) {
            session_start();
        }
    }
    /**
     * addError($message)
     *
     * Ajoute un message d'erreur à la pile de session
     *
     * @param   String  $message  [Message d'erreur]
     *
     * @return  void
     */
    public function addError($message) {
        $_SESSION["message"]["error"][] = $message;
    }

    /**
     * addWarning($message)
     *
     * Ajoute un message d'alerte à la pile de session
     *
     * @param   String  $message  [Message d'alerte]
     *
     * @return  void
     */
    public function addWarning($message) {
        $_SESSION["message"]["warning"][] = $message;
    }

    /**
     * addSuccess($message)
     *
     * Ajoute un message de succès à la pile de session
     *
     * @param   String  $message  [Message de succès]
     *
     * @return  void
     */
    public function addSuccess($message) {
        $_SESSION["message"]["success"][] = $message;
    }
    
    /**
     * getMessages($filter="all")
     *
     * Récupère les messages enregistrés en session
     *
     * @param   String        $filter [Filtre de tri]
     *
     * @return  String|Array  [Pile des messages]
     */
    public function getMessages($filter="all") {
        if(empty($_SESSION["message"])) {
            return array();
        }
        if($filter==="all") {
            return $_SESSION["message"];
        }
        else {
            $filter = strtolower($filter);
            if(array_key_exists($filter, $_SESSION["message"])) {
                return $_SESSION["message"][$filter];
            }
        }
        return array();
    }
    /**
     * showMessages()
     *
     * Affiche les messages de la pile de session
     *
     * @return  String
     */
    public function showMessages() {
        $html = "";
        if(!empty($_SESSION["message"]["error"])) {
            $html .=    "<div class='alert alert-danger'>
                            <p>
                                <span class='glyphicon glyphicon-remove'> </span> Erreur
                                <hr>
                            </p>" . implode('<br>', $_SESSION["message"]["error"]).
                        "<br></div>";
        }
        if(!empty($_SESSION["message"]["warning"])) {
            $html .=    "<div class='alert alert-warning'>
                            <p>
                                <span class='glyphicon glyphicon-warning-sign'> </span> Alerte
                                <hr>
                            </p>" . implode('<br>', $_SESSION["message"]["warning"]).
                        "<br></div>";
        }
        if(!empty($_SESSION["message"]["success"])) {
            $html .=    "<div class='alert alert-success'>
                            <p>
                                <span class='glyphicon glyphicon-ok'> </span> Succès
                                <hr>
                            </p>" . implode('<br>', $_SESSION["message"]["success"]).
                        "<br></div>";
        }
        $_SESSION['message'] = array();
        return $html;
    }
}
