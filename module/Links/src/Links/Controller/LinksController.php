<?php

namespace Links\Controller;
use Links\Form\LinkForm;
use Links\Model\Link;
use Links\Model\LinkTable;
use Links\Model\User;
use Zend\Console\Response;
use Zend\Http\Headers;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class LinksController extends AbstractRestfulController
{
    protected $linkTable;
    protected $commentTable;
    protected $userTable;
    protected $voteTable;

    public function getList()
    {
        $results = $this->getLinkTable()->fetchAll();
        $data=array();
       foreach($results as $result) {
           $result->votesUp=$this->getVoteTable()->getVotesUp($result->link_id);
           $result->votesDown= $this->getVoteTable()->getVotesDown($result->link_id);
           $data[]=$result;
        }

        $response=new \Zend\Http\Response();
        $response->setHeaders(new Headers('Content/type', 'application/json'), new Headers('charset', 'UTF-8'));
        $response->setContent(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG));
        return $response;
    }
    public function get($id)
    {
        $link = $this->getLinkTable()->getLink($id);
        $comments=$this->getCommentTable()->getComments($id);
        $data = array();
        $br=0;

        $link->votesUp=$this->getVoteTable()->getVotesUp($link->link_id);
        $link->votesDown= $this->getVoteTable()->getVotesDown($link->link_id);
        foreach($comments as $result) {
            $data[] = $result;
            $user=$this->getUserTable()->getUserById($data[$br]->user_id);
            $username=$user->username;
            $data[$br]->user_name=$username;
            $br++;
        }


        $data=array('link' => $link, 'comments' => $data);
        $response=new \Zend\Http\Response();
        $response->setHeaders(new Headers('Content/type', 'application/json'), new Headers('charset', 'UTF-8'));
        $response->setContent(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG));

        return $response;
    }

    public function create($data)
    {


        $api_key=$data["api_key"];

        if($this->getUserTable()->getUserByApiKey($api_key)) {
            $form = new LinkForm();
            $link = new Link();
            $form->setInputFilter($link->getInputFilter());
            $form->setData($data);
            $id = null;
            if ($form->isValid()) {
                $link->exchangeArray($form->getData());
                $id = $this->getLinkTable()->saveLink($link);
            }

            return new JsonModel(array('link_id' => $id));
        }
        else{
            return "You don't have privileges to post data";
        }
    }

    public function update($id, $data)
    {
        $api_key=$data["api_key"];


        if($this->getUserTable()->getUserByApiKey($api_key)) {
            $linkCont = [
                'link_id' => $id,
                'url' => $data["url"],
                'description' => $data["description"]
            ];

            $link = $this->getLinkTable()->getLink($id);
            $form = new LinkForm();
            $form->bind($link);
            $form->setInputFilter($link->getInputFilter());
            $form->setData($linkCont);

            if ($form->isValid()) {

                $id = $this->getLinkTable()->updateLink($linkCont);
            }

            return $data;
        }
        else{
            return "You don't have privileges to update data!";
        }
    }

    public function delete($id)
    {
        
        $api_key=$this->params()->fromQuery('api_key');

        if($this->getUserTable()->getUserByApiKey($api_key))
        {
            $this->getLinkTable()->deleteLink($id);

            return "Deleted";
        }
        else{
            return "You don't have privileges to delete data!";
        }
    }
    public function getLinkTable()
    {
        if (!$this->linkTable) {
            $sm = $this->getServiceLocator();
            $this->linkTable = $sm->get('Links\Model\LinkTable');
        }
        return $this->linkTable;
    }

    public function getCommentTable()
    {
        if (!$this->commentTable) {
            $sm = $this->getServiceLocator();
            $this->commentTable = $sm->get('Links\Model\CommentTable');
        }
        return $this->commentTable;
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Links\Model\UserTable');
        }
        return $this->userTable;
    }

    public function getVoteTable()
    {
        if (!$this->voteTable) {
            $sm = $this->getServiceLocator();
            $this->voteTable = $sm->get('Links\Model\VoteTable');
        }
        return $this->voteTable;
    }


}