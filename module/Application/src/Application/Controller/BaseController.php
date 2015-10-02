<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
  * Base Controller, providing Doctrine ObjectManager and maybe other stuff
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class BaseController extends AbstractActionController implements ObjectManagerAwareInterface
{

    /**
     * @var Doctrine\ORM\ObjectManager
     */
    protected $objectManager;

    /**
     * Injecting ObjectManager
     * @return null
     */
    public function __construct($om)
    {
	     $this->objectManager = $om;
    }

    /**
     * Set current instance of ObjectManager
     * @return null
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Return current instance of ObjectManager
     * @return Doctrine\ORM\ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

}
