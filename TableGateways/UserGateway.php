<?php
namespace Src\TableGateways;

class UserGateway extends AbstractGateway {

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'user';
    }

    public function findAll() {
        $query = 'SELECT id, name, admin FROM ' . $this->_table;
        return $this->_query($query);
    }

    public function find($id) {
        $query =
        'SELECT id, name, admin '.
        'FROM ' . $this->_table . ' '.
        'WHERE id = ?';

        return $this->_query($query, array($id));
    }

    public function findByName($name) {
        $query =
        'SELECT id, name, admin, password '.
        'FROM ' . $this->_table . ' '.
        'WHERE name = ?';

        return $this->_query($query, array($name));
    }

    public function insert(Array $input) {

        // Hash password
        $passHash = $this->passHashing($input['password']);

        $query = 'INSERT INTO ' . $this->_table . ' (name, password, admin) VALUES(:name, :password, :admin)';
        return $this->_command($query,
                               array(
                               'admin' => (int) @$input['admin'] ?? 0,
                               'name' => $input['name'],
                               'password' => $passHash,
                            ));
    }

    private function passHashing($password): string {
        return password_hash($password,
                             PASSWORD_DEFAULT,
                             [ 'cost' => 10 ]);
    }

    public function update($identifier, Array $input) {

        $passHash = $this->passHashing($input['password']);

        $query = 'UPDATE ' . $this->_table . ' SET name = :name, ' . 
        'password = :password, ' .
        'admin = :admin ' .
        'WHERE id = :id';

        return $this->_command(
            $query,
            array(
                'id' => (int) $identifier,
                'name' => $input['name'],
                'password' => $passHash,
                'admin' => $input['admin']
            ));      
    }

    public function deleteSingle($identifier) {
        $query = 
        'DELETE FROM ' . $this->_table . ' '.
        'WHERE id = :id;';

        return $this->_command($query, array('id' => $identifier));
    }
}
