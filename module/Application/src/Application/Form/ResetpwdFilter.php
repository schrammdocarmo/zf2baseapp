<?php
namespace Application\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
  * Reset password form filter
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */ 
class ResetpwdFilter extends InputFilter {
 
    public function __construct($em = false) {

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
                        'min' => 8,
                        'max' => 100,
                    ),
                ),
            )
        ));

	$this->add(array(
            'name' => 'password_confirmation',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                        'name' => 'Identical',
                        'options' => array(
                                'token' => 'password', // name of first password field
                        )
                ),
            ),
        ));



    }
}
