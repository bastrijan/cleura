<?php
namespace Src\TableGateways;

class UserGateway {

    private $_db = null;

    public function __construct($db) {
        $this->_db = $db;
    }

    public function findAll() {
        $query = "SELECT id, name, admin FROM user";
        return $this->_query($query);
    }

    public function find($id) {
        $query =
        "SELECT id, name, admin ".
        "FROM user ".
        "WHERE id = ?";

        return $this->_query($query, array($id));
    }

    public function insert(Array $input) {
        throw(new \Exception("To be implemented, for admin user only"));

        // Hash password
        $passHash = password_hash($input["password"],
                    PASSWORD_DEFAULT,
                    [ "cost" => 10 ]);

        $query = "INSERT INTO user(name, password, admin) VALUES(:name, :password, :admin)";
        return $this->_command($query,
                               array(
                               "admin" => (int) @$input["admin"] ?? 0,
                               "name" => $input["name"],
                               "password" => $passHash,
                            ));
    }

    public function update($identifier, Array $input) {
        throw(new \Exception("To be implemented, for admin only"));
    }

    public function deleteSingle($identifier) {
        throw(new \Exception("To be implemented, for admin only"));
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

    /**
     * Utility method for validating if supplied password is valid for a password hash
     * @param string $password The password
     * @param string $hashedPassword The password hash (ie. what is stored in database: simple_forum.user.password field)
     **/
    private function _validatePassword($password, $hashedPassword) {
            return password_verify($password, $hashedPassword);
    }
}
