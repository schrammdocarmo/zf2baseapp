<?php
namespace Application\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
  * User registration form filter
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */ 
class RegisterFilter extends InputFilter {

    public function __construct($em = false) {

        $this->add(array(
            'name' => 'company',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
	    ),
	    'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'first_name',
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
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
            ),
        ));
 
        $this->add(array(
            'name' => 'last_name',
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
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
            ),
        ));
 
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
	    'validators' => array(
			      array(
				    'name' => 'DoctrineModule\Validator\NoObjectExists',
				    'options' => array(
        				'object_repository' => is_object($em)?$em->getRepository('Application\Entity\User'):null,
				        'object_manager'  => $em,
				        'fields' => array('email'),
				        'messages' => array(
            					'objectFound' => 'This email address does already exist in our database. Please choose another one or click <a href="/login/">here</a> to login.'
				        ),
			    	    )
			      )
	    		   ),
        ));


	$this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
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


   public function getInputFilterSpecification()
    {
        return array();
	}

}
