<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
 
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BaseController extends AbstractActionController implements ObjectManagerAwareInterface
{


    /**
     * @var Doctrine\ORM\ObjectManager
     */
    protected $objectManager;

    /**
     * 
     */
    public function __construct($om)
    {
	$this->objectManager = $om;
    }

    /**
     *
     *
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * for managing entities via Doctrine
     * @return Doctrine\ORM\ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

}
