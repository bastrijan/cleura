<?php
namespace Src\TableGateways;

use Src\Services\DbSingleton;

abstract class AbstractGateway {

    protected $_db = null;
    protected $_table = '';

    public function __construct() {
        $this->_db = DbSingleton::getConnection();
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
