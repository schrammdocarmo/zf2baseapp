<?php 
namespace Application\Form;
 
use Zend\Form\Element;
use Zend\Form\Form;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
  * User profile form 
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class BaseForm extends Form implements ObjectManagerAwareInterface {

    /**
     *
     * @var 
     */
    private $translator = null;

    /**
     *
     * @var Doctrine\ORM\ObjectManager
     */
    private $objectManager;


    public function translate($label) {
        return $this->getTranslator()->translate($label);
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    public function __construct($name=null, $translator=null, $objectManager=null, $options=array())
    {
	$this->setObjectManager($objectManager);
	$this->setTranslator($translator);

        parent::__construct($name, $options);
    }

    public function init() {
	parent::init();
    }

}
