<?php
namespace Src\TableGateways;

class PostGateway extends AbstractGateway {

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'post';
    }

    public function findAll() {
        $query = 'SELECT id, forum_id, user_id, message, created FROM ' . $this->_table;
        return $this->_query($query);
    }

    public function find($id) {
        $query =
        'SELECT id, forum_id, user_id, message, created '.
        'FROM ' . $this->_table . ' '.
        'WHERE id = ?';

        return $this->_query($query, array($id));
    }

    public function insert(Array $input) {
        $query = 'INSERT INTO ' . $this->_table . ' (forum_id, user_id, message) VALUES (:forum_id, :user_id, :message)';
        
        return $this->_command(
                $query,
                array(
                    'forum_id' => $input['forum_id'] ?? null,
                    'user_id' => $input['user_id'] ?? null,
                    'message' => $input['message'] ?? null,
                ));
        
    }

    public function update($identifier, Array $input) {
        $query = 'UPDATE ' . $this->_table . ' SET message = :message WHERE id = :id';

        return $this->_command(
            $query,
            array(
                'id' => (int) $identifier,
                'message' => $input['message']
            ));      
    }

    public function deleteSingle($identifier) {
        $query = 
        'DELETE FROM ' . $this->_table . ' '.
        'WHERE id = :id;';

        return $this->_command($query, array('id' => $identifier));
    }
}
