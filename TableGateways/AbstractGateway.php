<?php
namespace Src\TableGateways;

abstract class AbstractGateway {

    private $_db = null;

    public function __construct($db) {
        $this->_db = $db;
    }

    public function findAll() {
        $query = "SELECT id, name FROM forum";
        return $this->_query($query);
    }

    public function find($id) {
        $query =
        "SELECT id, name ".
        "FROM forum ".
        "WHERE id = ?";

        return $this->_query($query, array($id));
    }

    public function insert(Array $input) {
        $query = "INSERT INTO forum (name) VALUES (:name)";
        
        return $this->_command(
                $query,
                array(
                    "name" => $input["name"] ?? null,
                ));
        
    }

    public function update($identifier, Array $input) {
        $query =
        "UPDATE forum SET ".
        "name = :name ".
        "WHERE id = :id";

        return $this->_command(
                $query,
                array(
                    "id" => (int) $identifier,
                    "name" => $input["name"]
                ));
    }

    public function deleteSingle($identifier) {
        $query = 
            "DELETE FROM forum ".
            "WHERE id = :id;";

        return $this->_command($query, array("id" => $identifier));
    }

    protected function _query($query,
                              $params = null,
                              $isCommand = false) {
        try {
            if ($params) {
                $statement = $this->_db->prepare($query);
                $statement->execute($params);	  
            }
            else {
                $statement = $this->_db->query($query);
            }

            if ($isCommand) {
                return $statement->rowCount();
            }
            else {
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                return $result;
            }
        }
        catch (\PDOException $e) {
            exit($e->getMessage());
        }      
    }

    protected function _command($query, $params) {
      return $this->_query($query, $params, true);
    }  
}
