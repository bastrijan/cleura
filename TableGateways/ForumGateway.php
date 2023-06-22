<?php
namespace Src\TableGateways;

class ForumGateway extends AbstractGateway {
    
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'forum';
    }

    public function findAll() {
        $query = 'SELECT id, name FROM ' . $this->_table;
        return $this->_query($query);
    }

    public function find($id) {
        $query =
        'SELECT id, name '.
        'FROM ' . $this->_table . ' ' .
        'WHERE id = ?';

        return $this->_query($query, array($id));
    }

    public function insert(Array $input) {
        $query = 'INSERT INTO ' . $this->_table . ' (name) VALUES (:name)';
        
        return $this->_command(
                $query,
                array(
                    'name' => $input['name'] ?? null,
                ));
        
    }

    public function update($identifier, Array $input) {
        $query =
        'UPDATE ' . $this->_table . ' SET '.
        'name = :name '.
        'WHERE id = :id';

        return $this->_command(
                $query,
                array(
                    'id' => (int) $identifier,
                    'name' => $input['name']
                ));
    }

    public function deleteSingle($identifier) {
        $query = 
            'DELETE FROM ' . $this->_table . ' '.
            'WHERE id = :id';

        return $this->_command($query, array('id' => $identifier));
    }
}
