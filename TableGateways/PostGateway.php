<?php
namespace Src\TableGateways;

class PostGateway {

    private $_db = null;

    public function __construct($db) {
        $this->_db = $db;
    }

    public function findAll() {
        $query = "SELECT id, forum_id, user_id, message, created FROM post";
        return $this->_query($query);
    }

    public function find($id) {
        $query =
        "SELECT id, forum_id, user_id, message, created ".
        "FROM post ".
        "WHERE id = ?";

        return $this->_query($query, array($id));
    }

    public function insert(Array $input) {
        throw(new \Exception("To be implemented"));
    }

    public function update($identifier, Array $input) {
        $query = "UPDATE post SET message = :message WHERE id = :id";

        return $this->_command(
            $query,
            array(
                "id" => (int) $identifier,
                "message" => $input["message"]
            ));      
    }

    public function deleteSingle($identifier) {
        $query = 
        "DELETE FROM post ".
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
