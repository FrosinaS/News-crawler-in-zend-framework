<?php
namespace Links\Controller;
use Links\Form\CommentForm;
use Links\Model\Comment;
use Links\Model\CommentTable;
use Links\Model\UserTable;
use Zend\Http\Headers;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;


class CommentsController extends AbstractRestfulController
{
    protected $commentTable;
    protected $linkTable;
    protected $userTable;

    public function getList()
    {
        $id=$this->getEvent()->getRouteMatch()->getParam('id');
        $comments=$this->getCommentTable()->getComments($id);
        return new JsonModel($comments);
    }

    public function get($id)
    {
        $comid=0;
        $comid=$this->getEvent()->getRouteMatch()->getParam('comment');
        if($comid == 0)
        {
            return $this->getList();
        }
        $comments=$this->getCommentTable()->getComment($comid);
        $arr=array('comments' => $comments);
        $response=new \Zend\Http\Response();
        $response->setHeaders(new Headers('Content/type', 'application/json'), new Headers('charset', 'UTF-8'));
        $response->setContent(json_encode($comments, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG));

        return new JsonModel($arr);

    }

    public function create($data)
    {

        $api_key=$data["api_key"];

        if($this->getUserTable()->getUserByApiKey($api_key)) {
            $form = new CommentForm();
            $comment = new Comment();
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($data);
            $id = null;
            if ($form->isValid()) {
                $comment->exchangeArray($form->getData());
                $id = $this->getCommentTable()->saveComment($comment);
            }
            $response=new Response();
            $response->setHeaders(new Headers('Content/type', 'application/json'));
            $data['comment_id']=$id;
            return new JsonModel(array($data));
        }
        else{
            return "You don't have privileges to post data!";
        }
    }

    public function update($id, $data)
    {

        $comment_id=$this->getEvent()->getRouteMatch()->getParam('comment');
        $link_id=$this->getEvent()->getRouteMatch()->getParam('id');
        $api_key=$data["api_key"];

        if($this->getUserTable()->getUserByApiKey($api_key)) {
            $comm = [
                'comment_id' => $comment_id,
                'comment_text' => $data["comment_text"],
                'link_id' => $link_id,
                'user_id' => $data["user_id"]
            ];

            $comment = new Comment();
            $form = new CommentForm();
            $form->bind($comment);
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($comm);

            if ($form->isValid()) {

                $id = $this->getCommentTable()->updateComment($comm);
            }
            $response=new Response();
            $response->setHeaders(new Headers('Content/type', "application/json"));
            return new JsonModel(array($comm));
        }
        else{
            return "You don't have privileges for updating!";
        }
    }

    public function delete($id)
    {

        $comment_id=$this->getEvent()->getRouteMatch()->getParam('comment');
        $api_key=$this->params()->fromQuery('api_key');

        if($this->getUserTable()->getUserByApiKey($api_key)) {

            $response=$this->getCommentTable()->deleteComment($comment_id);
            return new JsonModel(array('comment_id' => $response));
        }
        else
        {
            return "You don't have privileges for deleting.";
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

}