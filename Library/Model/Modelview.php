<?php

namespace Library\Model;

/**
 * Classe permettant de construire un modèle à partir d'une vue existant en base de données
 */
abstract class Modelview extends Model {

    /**
     * __construct($connexionName)
     *
     * Desc
     *
     */
    public function __construct($connexionName) {
        parent::__construct($connexionName);
    }

    /**
     * delete($where)
     *
     * Desc
     *
     * @param   String     $where  [Condition]
     *
     * @return  Exception  [Message d'erreur]
     */
    public function delete($where) {
        throw new \Exception("Error DELETE impossible sur une view");
    }

    /**
     * delete($where)
     *
     * Desc
     *
     * @param   String     $value_primary  [Nom de la clé primaire]
     *
     * @return  Exception  [Message d'erreur]
     */
    public function deleteByPrimary($value_primary) {
        throw new \Exception("Error DELETE par clé primaire impossible sur une view");
    }

    /**
     * delete($where)
     *
     * Desc
     *
     * @param   Array      $data  [Tableau associatif des données]
     *
     * @return  Exception  [Message d'erreur]
     */
    public function insert($data) {
        throw new \Exception("Error INSERT impossible sur une view");
    }
}