<?php
namespace WebSite\Controller;
use \Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Http\Client;
use Zend\Di\ServiceLocator;
use \Zend\Json;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
        $url = $helper->__invoke('/LinksWebSite/public/links');
        $request->setUri($url);
        $client = new Client();
        $response = $client->send($request);

        $linkContent=json_decode($response->getContent(), true, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG);
        $view=new ViewModel();
        $template = new ViewModel(array('data' => $linkContent));
        $template->setTemplate('index/link-template');
        $view->addChild($template, 'template');
        return $view;
    }


    public function commentsAction()
    {
        $sm = $this->getServiceLocator();
        $auth = $sm->get('zfcuserauthservice');
        $api=0;
        $id_user=0;
        if ($auth->hasIdentity()) {
            $user_edit = $auth->getIdentity();
            $id_user=$user_edit->getId();
        }
        $id=$this->getEvent()->getRouteMatch()->getParam('id');
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
        $url = $helper->__invoke('/LinksWebSite/public/links/'.$id);
        $request->setUri($url);
        $client = new Client();
        $response = $client->send($request);
        $linkContent=json_decode($response->getContent(), true, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG);
        $view=new ViewModel();
        if($linkContent == NULL)
        {
            $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
            $url = $helper->__invoke('/LinksWebSite/public/error-404');
            $this->plugin('redirect')->toUrl($url);
            return FALSE;
        }
        $template = new ViewModel(array('data' => $linkContent));
        $template->setTemplate('index/comments-template');
        $view->addChild($template, 'template');
        return $view;

    }

    public function getVoteAction()
    {

        $sm = $this->getServiceLocator();
        $auth = $sm->get('zfcuserauthservice');
        $user_edit=0;
        $id_user=0;
        if ($auth->hasIdentity()) {
            $user_edit = $auth->getIdentity();
            $id_user=$user_edit->getId();
        }
        $link_id=$this->getEvent()->getRouteMatch()->getParam('id');
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
        $url = $helper->__invoke('/LinksWebSite/public/links/'.$link_id.'/votes/'.$id_user);
        $request->setUri($url);
        $client = new Client();
        $response = $client->send($request);
        $linkContent=json_decode($response->getContent(), true);
        $response=new Response();
        $response->getHeaders()->addHeaders(array('Content-Type' =>'application/json'));

        return new JsonModel($linkContent);
    }

}