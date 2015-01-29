<?php
namespace WebSite\Controller;
use Zend\Dom\DOMXPath;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Mail\Headers;
use Zend\Mvc\Controller\AbstractActionController;
use \WebSite\Form\GeneratingLinksForm;
use \WebSite\Form\Validator;

class AdminController extends AbstractActionController
{

    function adminPanelAction()
    {

        $sm = $this->getServiceLocator();
        $auth = $sm->get('zfcuserauthservice');
        $user_edit=0;
        $admin='';
        if ($auth->hasIdentity()) {
            $user_edit = $auth->getIdentity();
            $admin=$user_edit->getType();
        }
        if($admin != 'admin')
        {
            $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
            $url = $helper->__invoke('/LinksWebSite/public/news');
            $this->plugin('redirect')->toUrl($url);
            return FALSE;
        }


        $form = new GeneratingLinksForm();
        $form->get('submit')->setValue('Generate');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $validator = new Validator();
            $form->setInputFilter($validator->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $sm = $this->getServiceLocator();
                $auth = $sm->get('zfcuserauthservice');
                if ($auth->hasIdentity()) {
                    $user_edit = $auth->getIdentity();
                    $api_key=$user_edit->getApiKey();
                }

                $url1=$this->getRequest()->getPost('link_url');
                $description=$this->getRequest()->getPost('description');

                $request = new Request();
                $request->setMethod('POST');
                $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
                $url = $helper->__invoke('/LinksWebSite/public/links');
                $request->setUri($url);
                $data=array('url' => $url1,
                'description' => $description,
                'api_key' => $api_key);
                $request->getPost()->fromArray($data);


                $client = new Client();
                $client->setEncType(Client::ENC_FORMDATA);

                $response = $client->send($request);
                $form = new GeneratingLinksForm();
                return array('form' =>$form);


            }

        }
        return array('form' => $form);


    }

    function generateLinks($link, $label, $description){

        $html = file_get_contents($link);

        $dom = new \DOMDocument();
        @$dom->loadHTML($html);


// grab all the on the page
        $xpath = new DOMXPath($dom);
        $parseDes=explode(':', $description);
        $parseLi=explode(':', $label);
        if($parseLi[0] == 'id')
        {
            @$links=$xpath->query("/html/body//*[@id='".$parseLi[1]."']//a");
        }
        else
        {
            @$links=$xpath->query("/html/body//*[@class='".$parseLi[1]."']//a");
        }

        if($parseDes[0] == 'id')
        {
            @$descriptions=$xpath->query("/html/body//*[@id='".$parseDes[1]."']");
        }
        else
        {
            @$descriptions=$xpath->query("/html/body//*[@class='".$parseDes[1]."']");
        }

        $result=array();
        for ($i = 0; $i < $links->length; $i++) {
            $href = $links->item($i);
            $url = $href->getAttribute('href');
            if($this->substr_startswith($url, "/")) {
               $url= $link.$url;
            }

            if(!empty($descriptions->item($i)->textContent)){
                $des=$descriptions->item($i)->textContent;
            }
            else{
                $des="/";
            }

            $ar=array(trim($url) => trim($des));
            array_push($result, $ar);

        }

        return $result;

    }

    function substr_startswith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
