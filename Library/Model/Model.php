<?php

namespace Library\Model;

/**
 * Classe abstraite permettant de construire un modèle à partir d'une table existant en base de données
 *
 */
abstract class Model {
    
    /**
     * Objet base de données
     *
     * @var Class \Library\Model\Connexion
     */
    private $database;
    
    /**
     * @var String
     */
    protected $table;
    
    /**
     * @var String
     */
    protected $primary;

    /**
     * @var String
     */
    private $connexionName;

    /**
     * Setter de $connexionName
     *
     * @param   String   [Nom de la connexion]
     *
     * @return  void
     */
    public function setConnexionName($connexionName) { $this->connexionName = $connexionName; }

    /**
     * Setter de $primary
     *
     * @param   String   $primary  [Nom de la clé primaire]
     *
     * @return  void
     */
    public function setPrimary($primary) { $this->primary = $primary; }

    /**
     * __construct($connexionName)
     *
     * Initialise le modèle pour la connexion transmise en paramètre
     *
     * @param   String  $connexionName  [Nom de la connexion à utiliser]
     *
     * @return  void
     */
    public function __construct($connexionName) {
        $classConnexion = \Library\Model\Connexion::getInstance();
        $this->database = $classConnexion::getConnexion($connexionName);
    }
    
    /**
     * findByPrimary($value_primary, $fields="*")
     *
     * Récupère un élément en fonction de la clé primaire
     *
     * @param   String|Int  $value_primary  [Valeur de la clé primaire à selectionner]
     * @param   String      $fields         [Liste des champs à selectionner]
     *
     * @return  Array       [Tableau d'objets]
     */
    public function findByPrimary($value_primary, $fields="*") {
        $sql = $this->database->prepare("SELECT $fields FROM `{$this->table}` WHERE `{$this->primary}`=:primary");
        $sql->execute( array("primary" => $value_primary) );
        return $sql->fetchAll();
    }
    
    /**
     * fetchAll($where=1, $fields="*")
     *
     * Récupère un ou plusieurs éléments en fonction d'une condition
     *
     * @param   Boolean  $where   [Condition]
     * @param   String   $fields  [Liste des champs à selectionner]
     *
     * @return  Array    [Tableau d'objets]
     *
     */
    public function fetchAll($where=1, $fields="*") {
        $sql = $this->database->prepare("SELECT $fields FROM `{$this->table}` WHERE $where");
        $sql->execute();
        return $sql->fetchAll();
    }
    
    /**
     * insert($data)
     *
     * Effectue une insertion en base
     *
     * @param   Array    $data  [Tableau associatif des données]
     *
     * @return  Boolean  [Retourne true si l'élément est effectivement inséré sinon false]
     *
     * @example
     *
     *      $data = array(  "nom" => "value,
     *                      "description" => "value",
     *                      ...
     *              );
     *      $listFields = `nom`,`description`, ...
     *      $listValues = :nom,:description, ...
     */
    public function insert($data) {
        $listFields     = '`' . implode('`,`', array_keys($data)) . '`';
        $listValues     = ':' . implode(',:', array_keys($data));
        
        $sql = $this->database->prepare("INSERT INTO `{$this->table}` ($listFields) VALUES ($listValues)");
        unset( $listFields, $listValues );
        return $sql->execute( $data );
    }
    
    /**
     * updateByFields($fieldName, $data, $strict)
     *
     * Effectue une mise à jour en base suivant un champ particulier
     *
     * @param   String   $fieldName  [Champ selon lequel la mise à jour sera effectuée]
     * @param   Array    $data       [Tableau associatif des données]
     * @param   Boolean  $strict     [Retour strict ou non, permet la prise en compte ou non des requêtes n'affectant aucun élément]
     *
     * @return  Boolean  [Retourne true si l'élément est effectivement mis à jour sinon false]
     */
    public function updateByFields($fieldName, $data, $strict=true) {
        $listFieldsValues   = $this->updateListFieldsValues($data, $fieldName);
        $sql                = $this->database->prepare("UPDATE `{$this->table}` SET $listFieldsValues WHERE `$fieldName`=:$fieldName");
        unset($listFieldsValues);
        //var_dump($sql->execute($data));
        return $this->returnAffectedRowBoolean($sql, $strict);
    }
    
    /**
     * update($where, $data, $strict)
     *
     * Effectue une mise à jour en base
     *
     * @param   Array    $data  [Tableau associatif des données]
     *
     * @return  Boolean  [Retourne true si l'élément est effectivement mis à jour sinon false]
     *
     */
    public function update($where, $data, $strict=true) {
        $listFieldsValues   = $this->updateListFieldsValues($data);
        $sql                = $this->database->prepare("UPDATE `{$this->table}` SET $listFieldsValues WHERE $where");
        unset($listFieldsValues);
        var_dump($sql->execute($data));
        return $this->returnAffectedRowBoolean($sql, $strict);
    }
    
    /**
     * updateListFieldsValues($data, $exclude=null)
     *
     * Desc
     *
     * @param  Array   $data     [Tableau associatif des données]
     * @param  String  $exclude  [Clé à exclure]
     *
     * @return String  [Retourne une chaîne des couples clé/valeur pour la requête SQL]
     */
    protected function updateListFieldsValues($data, $exclude=null) {
        $list = "";
        foreach($data as $key=>$value) {
            if($exclude==$key) {
                continue;
            }
            $list .= "`$key`=:$key,";
        }
        return substr($list, 0, -1);
    }
    
    /**
     * returnAffectedRowBoolean($query, $strict)
     *
     * En fonction du nombre de lignes affectées par la mise à jour, retourne true ou false
     *
     * @param   Object PDO  $query   [Objet PDO post execution]
     * @param   Boolean     $strict  [Retour strict ou non, permet la prise en compte ou non des requêtes n'affectant aucun élément]
     * 
     * @return  Boolean     [Retourne true pour un nombre de lignes affectées supérieur à 0 sinon false]
     */
    protected function returnAffectedRowBoolean($query, $strict) {
        //var_dump($query);
        if($query && (($strict && $query->rowCount()>0) || (!$strict && $query->rowCount()>=0))) { return true; }
        else { return false; }
    }
    
    /**
     * deleteByPrimary($value_primary)
     *
     * Desc
     *
     * @param   String|Int  $value_primary  [Valeur de la clé primaire à selectionner]
     * 
     * @return  Boolean     [Retourne true pour un nombre de lignes affectées supérieur à 0 sinon false]
     */
    public function deleteByPrimary($value_primary) {
        $sql = $this->database->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primary}`=:primary");
        $sql->execute( array("primary" => $value_primary) );
        return $this->returnAffectedRowBoolean($sql, true);
    }
    
    /**
     * delete($where)
     *
     * Desc
     *
     * @param   Boolean  $where  [Condition]
     * 
     * @return  Boolean  [Retourne true pour un nombre de lignes affectées supérieur à 0 sinon false]
     */    
    public function delete($where) {
        $sql = $this->database->prepare("DELETE FROM `{$this->table}` WHERE $where");
        $sql->execute();
        return $this->returnAffectedRowBoolean($sql, true);
    }
}
