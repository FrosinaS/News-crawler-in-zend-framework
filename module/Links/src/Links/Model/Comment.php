<?php
namespace Links\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;

class Comment {

    public $comment_id;
    public $comment_text;
    public $link_id;
    public $user_id;
    public $user_name;
    private $inputFilter;

    public function exchangeArray($data)
    {
        $this->comment_id     = (!empty($data['comment_id'])) ? $data['comment_id'] : null;
        $this->comment_text = (!empty($data['comment_text'])) ? $data['comment_text'] : null;
        $this->link_id  = (!empty($data['link_id'])) ? $data['link_id'] : null;
        $this->user_id  = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->user_name  = (!empty($data['user_name'])) ? $data['user_name'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'comment_text',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 1000,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'link_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

} 