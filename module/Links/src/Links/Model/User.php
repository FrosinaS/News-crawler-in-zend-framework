<?php
namespace Links\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;
class User {

    public $user_id;
    public $username;
    public $email;
    public $api_key;
    public $display_name;
    public $password;
    public $state;

    public function exchangeArray($data)
    {
        $this->user_id     = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->email  = (!empty($data['email'])) ? $data['email'] : null;
        $this->api_key     = (!empty($data['api_key'])) ? $data['api_key'] : null;
        $this->display_name = (!empty($data['display_name'])) ? $data['display_name'] : null;
        $this->password  = (!empty($data['password'])) ? $data['password'] : null;
        $this->state  = (!empty($data['state'])) ? $data['state'] : null;
    }

} 