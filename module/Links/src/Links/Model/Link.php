<?php
namespace Links\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;
class Link {

    public $link_id;
    public $url;
    public $description;
    private $inputFilter;
    public $votesUp;
    public $votesDown;

    public function exchangeArray($data)
    {
        $this->link_id     = (!empty($data['link_id'])) ? $data['link_id'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->description  = (!empty($data['description'])) ? $data['description'] : null;
        $this->votesUp     = (!empty($data['votes_up'])) ? $data['votes_up'] : null;
        $this->votesDown     = (!empty($data['votes_down'])) ? $data['votes_down'] : null;
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
                'name'     => 'url',
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


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

} 