<?php
namespace Application\Form;
use Zend\InputFilter\InputFilter;

/**
  * News form filter
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */ 
class NewsFilter extends InputFilter {
 
    public function __construct() {

        $this->add(array(
            'name' => 'title',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name' => 'text',
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
                        'max' => 65535,
                    ),
                ),
            ),
        ));



    }
}
