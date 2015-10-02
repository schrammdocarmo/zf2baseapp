<?php
namespace Application\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Regex as RegexValidator;

/**
  * Validate phone numbers in forms
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class Phone extends Element implements InputProviderInterface
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
    * Get a validator if none has been set.
    * @return ValidatorInterface
    */
    public function getValidator()
    {
        if (null === $this->validator) {
            $validator = new RegexValidator('/^([0-9\(\)\/\+ \-]*)$/');
            $validator->setMessage('Please use digits only',
                                    RegexValidator::NOT_MATCH);

            $this->validator = $validator;
        }

        return $this->validator;
    }

    /**
     * Sets the validator to use for this element
     * @param  ValidatorInterface $validator
     * @return Application\Form\Element\Phone
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Provide default input rules for this element
     * Attaches a phone number validator.
     * @return array
     */
    public function getInputSpecification()
    {
        return array(
            'name' => $this->getName(),
            'required' => true,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
            'validators' => array(
                $this->getValidator(),
            ),
        );
    }

}
