<?php
namespace Links\Model;
use Zend\Db\TableGateway\TableGateway;
use Links\Model\User;
use Zend\Db\Sql\Where;

class UserTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUserById($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('user_id' => $id));
        if (!$rowset) {
            throw new \Exception("Could not find row $id");
        }
        return $rowset->current();
    }

    public function getUserByApiKey($api_key)
    {
        $where = new Where();
        $where->like('api_key', $api_key);

        $rowset = $this->tableGateway->select($where);
        if (!$rowset) {
            throw new \Exception("Could not find row $api_key");
        }
        return $rowset->current();
    }

} 