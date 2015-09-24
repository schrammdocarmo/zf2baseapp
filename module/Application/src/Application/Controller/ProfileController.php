<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Contact;
use Zend\Session\Container;
use Application\Form;
use Application\Entity\Activity;

/**
  * User Profile
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class ProfileController extends AbstractActionController
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
    * Edit user Profile 
    *
    * @return array
    */
    public function indexAction()
    {

	$user = new Container('user');
	$userId = $user->identity->getId();

        $currentUser = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('id' => $userId));

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('profileForm');
        $form->setInputFilter(new Form\ProfileFilter($this->getObjectManager()));

        $request = $this->getRequest();
        if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {

			$currentUser->setEmail($request->getPost('email'));
			$currentUser->setCompany($request->getPost('company'));
                        $currentUser->setFirstName($request->getPost('first_name'));
                        $currentUser->setLastName($request->getPost('last_name'));
                        $currentUser->setAddress($request->getPost('address'));
                        $currentUser->setZipcode($request->getPost('zipcode'));
                        $currentUser->setCity($request->getPost('city'));
                        $currentUser->setCountry($request->getPost('country'));
                        $currentUser->setPhone($request->getPost('phone'));
                        $currentUser->setLastModified(new \DateTime("now"));

			$user->identity = $currentUser;

                        $activity = new Activity();
                        $activity->setUserId($userId);
                        $activity->setDescription('Updated profile');
                        $activity->setUri($request->getUriString());
                        $activity->setQuery(var_export($request->getQuery(),true));
                        $activity->setPost(var_export($request->getPost(),true));
                        $activity->setObject(var_export($currentUser,true));
                        $activity->setCreated(new \DateTime("now"));
                        $this->getObjectManager()->persist($activity);

                        $this->getObjectManager()->persist($currentUser);
                        $this->getObjectManager()->flush();

			//REDIRECT TO PROFILE PAGE
                        return $this->redirect()->toRoute('profile');

		}

	} else {

	$formData = array();
        $formData['id'] = $currentUser->getId();
        $formData['email'] = $currentUser->getEmail();
        $formData['company'] = $currentUser->getCompany();
        $formData['first_name'] = $currentUser->getFirstName();
        $formData['last_name'] = $currentUser->getLastName();
        $formData['address'] = $currentUser->getAddress();
        $formData['zipcode'] = $currentUser->getZipcode();
        $formData['city'] = $currentUser->getCity();
        $formData['country'] = $currentUser->getCountry();
        $formData['phone'] = $currentUser->getPhone();
        $form->setData($formData);

	}

	return array('form'=>$form);

    }

}
