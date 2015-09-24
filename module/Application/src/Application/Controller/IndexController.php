<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
  * Index page
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class IndexController extends AbstractActionController
{


    protected $_objectManager;


    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->_objectManager;
    }

    /**
      * Show index page
      *
      * @return array
      */
    public function indexAction()
    {
	return array();
    }

}
