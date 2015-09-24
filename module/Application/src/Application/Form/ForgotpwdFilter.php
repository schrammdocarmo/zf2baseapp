<?php
namespace Application\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
  * Forgot password form filter
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */  
class ForgotpwdFilter extends InputFilter {
 
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
                              array(
                                    'name' => 'DoctrineModule\Validator\ObjectExists',
                                    'options' => array(
                                        'object_repository' => is_object($em)?$em->getRepository('Application\Entity\User'):null,
                                        'object_manager'  => $em,
                                        'fields' => array('email'),
                                        'messages' => array(
                                                'noObjectFound' => 'This email address does not exist in our database. Please choose another one or click <a href="/register/">here to register</a>.'
                                        ),
                                    )
                              )
            ),

        ));

    }
}
