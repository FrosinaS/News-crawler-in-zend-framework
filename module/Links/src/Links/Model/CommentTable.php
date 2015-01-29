<?php
namespace Links\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class CommentTable {

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

    public function getComments($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('link_id' => $id));
        if (!$rowset) {
            throw new \Exception("Could not find row $id");
        }
        return $rowset;
    }

    public function getComment($comm_id)
    {
        $id  = (int) $comm_id;
        $rowset=null;
        if($id != null)
        {
            $rowset = $this->tableGateway->select(array('comment_id' => $comm_id));
            return $rowset->current();
        }

        if (!$rowset) {
            throw new \Exception("Could not find row $id");
        }
        return $rowset;
    }

    public function saveComment(Comment $comment)
    {

        $data = array(
            'comment_text' => $comment->comment_text,
            'link_id'  => $comment->link_id,
            'user_id' => $comment->user_id,
        );

        $this->tableGateway->insert($data);
        $id = $this->tableGateway->getLastInsertValue(); //Add this line

        return $id; // Add Return
    }

    public function updateComment($data)
    {
        $id = $data["comment_id"];

            if ($this->tableGateway->select(array('comment_id' => $id))) {
                $this->tableGateway->update($data, array('comment_id' => $id));
            } else {
                throw new \Exception('Id does not exist');
            }


        return $id; // Add Return
    }

    public function deleteComment($id)
    {
        $this->tableGateway->delete(array('comment_id' => $id));
        return $id;
    }

} 