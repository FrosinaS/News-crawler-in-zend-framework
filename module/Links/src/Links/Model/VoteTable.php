<?php
namespace Links\Model;

use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;


class VoteTable
{
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

    public function getVotesUp($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('link_id' => $id, 'vote'=> 1))->count();
        return $rowset;
    }
    public function getVotesDown($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('link_id' => $id, 'vote' => 2))->count();
        return $rowset;
    }
    public function getUserVote($id_user, $id_link)
    {
        $rowset = $this->tableGateway->select(array('user_id' => $id_user, 'link_id'=>$id_link));
        $row = $rowset->current();
        if (!$row) {
           return 0;
        }
        return $row;
    }

    public function saveVote(Vote $vote)
    {
        $data = array(
            'link_id' => $vote->link_id,
            'user_id'  => $vote->user_id,
            'vote' => $vote->vote,
        );

        $this->tableGateway->insert($data);
        $id = $this->tableGateway->getLastInsertValue();

        return $id;
    }

    public function updateVote(Vote $vote)
    {

        $data = array(
            'link_id' => $vote->link_id,
            'user_id'  => $vote->user_id,
            'vote' => $vote->vote,
        );
        $user_id=$vote->user_id;
        $link_id=$vote->link_id;
        $where = new Where();
        $where->NEST
            ->equalTo('user_id',$user_id)
            ->AND
            ->equalTo('link_id',$link_id)
            ->UNNEST;

        if ($this->tableGateway->select(array('link_id' => $link_id, 'user_id' => $user_id))) {

            $this->tableGateway->update($data, $where);

        }
        return true;
    }

    public function deleteVote($id_link, $id_user)
    {

        $this->tableGateway->delete(array('link_id' => $id_link, 'user_id'=>$id_user));
    }

}