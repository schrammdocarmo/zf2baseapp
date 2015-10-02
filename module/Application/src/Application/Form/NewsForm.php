<?php
namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
  * News form
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class NewsForm extends BaseForm
{

    /**
      * Add form elements and attributes
      */
    public function init()
    {

        $this->setName('Contact');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Title'),
            )
        ));

        $this->add(array(
            'name' => 'text',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate('Text'),
            )
        ));

        $this->add(new Element\Csrf('security'));

	      $this->add(array(
             'name' => 'button',
             'attributes' => array(
                'class' => 'btn btn-large btn-default btn-news',
                 'type' => 'button',
		             'onclick' => 'location.href="/news"',
                 'value' => $this->translate('Back'),
             ),
         ));

	       $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                'class' => 'btn btn-large btn-primary sbmt-news',
                 'type' => 'submit',
                 'value' => $this->translate('Send'),
             ),
         ));

    }

}
