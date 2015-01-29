<?php

namespace WebSite\Form;

use Zend\Captcha;
use Zend\Form\Element;
use Zend\Form\Form;

class GeneratingLinksForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('');


        $this->add(array(
            'name' => 'link_url',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => 'https://',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Enter url:   ',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'attributes' => array(
                'placeholder' => '',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Enter description:',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Generate',
            ),
        ));

    }
} 