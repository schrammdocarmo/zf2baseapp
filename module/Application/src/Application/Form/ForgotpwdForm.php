<?php
namespace Application\Form;
 
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;

/**
  * Password forgotten form 
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class ForgotpwdForm extends BaseForm {

    public function init() {

        $this->setName('Login');
        $this->setAttribute('method', 'post');

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

        $captchaImage = new CaptchaImage(  array(
                'font' => $_SERVER['DOCUMENT_ROOT'].'/fonts/arial.ttf',
                'width' => 200,
                'height' => 80,
                'dotNoiseLevel' => 40,
                'lineNoiseLevel' => 3)
        );
        $captchaImage->setImgDir($_SERVER['DOCUMENT_ROOT'].'/captcha');
        $captchaImage->setImgUrl('/captcha');

	$this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => $this->translate('Please verify you are human'),
                'captcha' => $captchaImage,
            ),
        ));
 
        $this->add(new Element\Csrf('security'));

	$this->add(array(
             'name' => 'submit',
             'attributes' => array(
                'class' => 'btn btn-large btn-primary',
                 'type' => 'submit',
                 'value' => $this->translate('Request password reset'),
             ),
         ));



    }
}
