<?php
namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
  * User profile form
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class ProfileForm extends BaseForm
{

    /**
      * Add form elements and attributes
      */
    public function init()
    {

        $this->setName('User');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('E-Mail Address'),
            ),
	          'validators' => array(
        			array(
			            'name' => 'EmailAddress'
        			),
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => $this->translate('Password'),
            )
        ));

        $this->add(array(
            'name' => 'company',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Company'),
            )
        ));

        $this->add(array(
            'name' => 'first_name',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('First name'),
            )
        ));

        $this->add(array(
            'name' => 'last_name',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Last name'),
            )
        ));


        $this->add(array(
            'name' => 'address',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Address'),
            )
        ));

        $this->add(array(
            'name' => 'zipcode',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Postal Code'),
            )
        ));

        $this->add(array(
            'name' => 'city',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('City'),
            )
        ));

	      $countries = $this->getObjectManager()->getRepository('Application\Entity\Country')->findAll();
        $countryOptions = array('' => '');
        if (is_array($countries) && sizeof($countries)>0) {
          foreach ($countries as $country) {
                $countryOptions[$country->getIso()] = $country->getName();
          }
        }
        $this->add(array(
            'name' => 'country',
            'type' => 'select',
            'options' => array(
                'label' => $this->translate('Country'),
		            'options' => $countryOptions,
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'Application\Form\Element\Phone',
            'options' => array(
                'label' => $this->translate('Phone Number'),
            )
        ));

        $this->add(new Element\Csrf('security'));

	      $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                'class' => 'btn btn-large btn-primary',
                 'type' => 'submit',
                 'value' => $this->translate('Save'),
             ),
         ));


    }

}
