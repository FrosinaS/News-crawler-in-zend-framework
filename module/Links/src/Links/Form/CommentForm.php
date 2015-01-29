<?php
namespace Links\Form;

use Zend\Form\Form;

class CommentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('comment');

        $this->add(array(
            'name' => 'comment_text',
            'type' => 'Text',
            'options' => array(
                'label' => 'Comment_text',
            ),
        ));
        $this->add(array(
            'name' => 'link_id',
            'type' => 'Hidden',

        ));
        $this->add(array(
            'name' => 'user_id',
            'type' => 'Hidden',

        ));

    }
}