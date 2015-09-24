<?php
namespace Application\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
  * Login form filter
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */ 
class LoginFilter extends InputFilter {
 
    public function __construct() {

        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
	    ),
	    'validators' => array(
                array('name' => 'EmailAddress'),
            )
        ));
 
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
	    ),
	    'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 5,
                        'max' => 32,
                    ),
                ),
            )
        ));



    }
}
