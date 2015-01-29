<?php
namespace Links\Form;

use Zend\Form\Form;

class LinkForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('link');

        $this->add(array(
            'name' => 'url',
            'type' => 'Text',
            'options' => array(
                'label' => 'Link_text',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Text',
            'options' => array(
                'label' => 'Description',
            ),
        ));

    }
}