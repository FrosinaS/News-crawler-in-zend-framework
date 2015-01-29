<?php
namespace Links\Controller;
use Links\Form\LinkForm;
use Links\Model\Vote;
use Links\Model\VoteTable;
use Zend\Filter\Boolean;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class VotesController extends AbstractRestfulController
{
    protected $userTable;
    protected $voteTable;
    public $api_key=0;

    public function getVote()
    {
        $id=$this->getEvent()->getRouteMatch()->getParam('id');
        $votesUp=$this->getVoteTable()->getVotesUp($id);
        $votesDown=$this->getVoteTable()->getVotesDown($id);
        return new JsonModel(array('votesUp' => $votesUp, 'votesDown' => $votesDown));
    }

    public function get($id)
    {
        $user_id=0;
        $user_id=$this->getEvent()->getRouteMatch()->getParam('user');
        if($user_id == 0)
        {
            return $this->getVote();
        }
        return new JsonModel(array($this->getVoteTable()->getUserVote($user_id, $id)));
    }

    public function create($data)
    {

        $link_id=$this->getEvent()->getRouteMatch()->getParam('id');

        $api_key=$data["api_key"];

        if($this->getUserTable()->getUserByApiKey($api_key)) {
            $vote = new Vote();
            $id = null;

            $vote->exchangeArray($data);
            $vote->link_id=$link_id;
            $id = $this->getVoteTable()->saveVote($vote);
            return true;
        }
        else{
            return false;
        }
    }

    public function update($id, $data)
    {
        $user_id=$this->getEvent()->getRouteMatch()->getParam('user');
        $link_id=$this->getEvent()->getRouteMatch()->getParam('id');

        $api_key=$data["api_key"];

        if($this->getUserTable()->getUserByApiKey($api_key)) {
            $vote = new Vote();
            $vote->exchangeArray($data);
            $vote->user_id=$user_id;
            $vote->link_id=$link_id;

            $id = $this->getVoteTable()->updateVote($vote);
            return new JsonModel(array($id));
        }
        else{
            return  new JsonModel(array("ne"));
        }

    }

    public function delete($id)
    {
        $user_id=$this->getEvent()->getRouteMatch()->getParam('user');
        $link_id=$this->getEvent()->getRouteMatch()->getParam('id');
        $api_key=$this->params()->fromQuery('api_key');

        if($this->getUserTable()->getUserByApiKey($api_key))
        {
            $this->getVoteTable()->deleteVote($link_id, $user_id);

            return "Deleted";
        }
        else{
            return "You don't have privileges to delete data!";
        }
    }
    public function getVoteTable()
    {
        if (!$this->voteTable) {
            $sm = $this->getServiceLocator();
            $this->voteTable = $sm->get('Links\Model\VoteTable');
        }
        return $this->voteTable;
    }
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Links\Model\UserTable');
        }
        return $this->userTable;
    }


}