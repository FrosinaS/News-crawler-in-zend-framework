<?php
namespace Links\Model;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class LinkTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
            $select->order('link_id DESC');
        });
        return $resultSet;
    }

    public function getLink($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('link_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveLink(Link $link)
    {

        $data = array(
            'url' => $link->url,
            'description'  => $link->description,
        );

        $this->tableGateway->insert($data);
        $id = $this->tableGateway->getLastInsertValue();
        return $id;
    }



        public function updateLink($data)
    {

        $id = $data["link_id"];

        if ($this->tableGateway->select(array('link_id' => $id))) {
                $this->tableGateway->update($data, array('link_id' => $id));
            } else {
                throw new \Exception('Id does not exist');
            }


        return $id; // Add Return
    }

    public function deleteLink($id)
    {
        $this->tableGateway->delete(array('link_id' => $id));
    }

} 