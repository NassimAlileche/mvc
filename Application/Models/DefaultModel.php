<?php

namespace Application\Models;

/**
 *
 *
 */
class DefaultModel extends \Library\Model\Model {
	
    /**
     * Nom de la table correspondant au modèle User
     * @var string
     */
    protected $table = 'default_model';

    /**
     * Clé primaire de la table correspondant au modèle User
     * @var string
     */
    protected $primary = 'id';
    /**
     * Ensemble des paramètres nécessaires pour valider les opérations GET, POST, PUT ou DELETE
     * @var array
     */
    protected $scheme = array(            //a virer

    );

    /**
     * Méthode __construct()
     *
     * Constructeur appelant le constructeur de Library\Model\Model et transmettant la connexion
     *
     * @return  void
     */
    public function __construct($connexionName) {
        parent::__construct($connexionName);
        parent::setPrimary($this->primary);
    }
}