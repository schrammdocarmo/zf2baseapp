<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Entity\News;
use Zend\Session\Container;
use Application\Form;
use Application\Entity\Activity;

/**
  * News
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class NewsController extends BaseController
{

   /**
    * News listing
    *
    * @return array
    */
    public function indexAction()
    {
	$news = $this->getObjectManager()->getRepository('Application\Entity\News')->findAll();
	return array('news' => $news);
    }

   /**
    * Add new News entry
    *
    * @return array
    */
    public function addAction()
    {


	$user = new Container('user');
	$userId = $user->identity->getId();

	$currentUser = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('id' => $userId));

	$news = new News();

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('newsForm');
        $form->setInputFilter(new Form\NewsFilter($this->getObjectManager()));

        $request = $this->getRequest();
        if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {

			$news->setTitle($request->getPost('title'));
			$news->setText($request->getPost('text'));
                        $news->setCreated(new \DateTime("now"));
                        $news->setLastModified(new \DateTime("now"));
			$news->setUserId($userId);

                        $activity = new Activity();
                        $activity->setUserId($userId);
                        $activity->setDescription('Removed News');
                        $activity->setUri($request->getUriString());
                        $activity->setQuery(var_export($request->getQuery(),true));
                        $activity->setPost(var_export($request->getPost(),true));
                        $activity->setObject(var_export($currentUser,true));
                        $activity->setCreated(new \DateTime("now"));
                        $this->getObjectManager()->persist($activity);

                        $this->getObjectManager()->persist($news);
                        $this->getObjectManager()->flush();

			//REDIRECT TO NEWS INDEX PAGE
                        return $this->redirect()->toRoute('news');

		}

	} else {

		$formData = array();
        	$formData['title'] = $news->getTitle();
	        $formData['text'] = $news->getText();
        	$form->setData($formData);

	}

	return array('form'=>$form);

    }

   /**
    * Edit News entry
    *
    * @return array
    */
    public function editAction()
    {
	$user = new Container('user');
	$userId = $user->identity->getId();

	$currentUser = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('id' => $userId));

	$id = $this->getRequest()->getQuery('id');
	if (empty($id)) $id = $this->getRequest()->getPost('id');

	$formManager = $this->serviceLocator->get('FormElementManager');
	$form = $formManager->get('newsForm');
	$form->setInputFilter(new Form\NewsFilter($this->getObjectManager()));

	if ($id>0) {

		$news = $this->getObjectManager()->getRepository('Application\Entity\News')->findOneBy(array('id' => $id));
                if (is_object($news)) {

		        $request = $this->getRequest();
		        if ($request->isPost()) {
                		$form->setData($request->getPost());
		                if ($form->isValid()) {

					$news->setTitle($request->getPost('title'));
					$news->setText($request->getPost('text'));
                        		$news->setCreated(new \DateTime("now"));
                        		$news->setLastModified(new \DateTime("now"));
					$news->setUserId($userId);

		                        $activity = new Activity();
                		        $activity->setUserId($userId);
                        		$activity->setDescription('Added News');
                        		$activity->setUri($request->getUriString());
                        		$activity->setQuery(var_export($request->getQuery(),true));
                        		$activity->setPost(var_export($request->getPost(),true));
                        		$activity->setObject(var_export($currentUser,true));
                		        $activity->setCreated(new \DateTime("now"));
		                        $this->getObjectManager()->persist($activity);

		                        $this->getObjectManager()->persist($news);
                		        $this->getObjectManager()->flush();

					//REDIRECT TO NEWS INDEX PAGE
                		        return $this->redirect()->toRoute('news');

				}
			}

		}

		$formData = array();
        	$formData['id'] = $news->getId();
        	$formData['title'] = $news->getTitle();
	        $formData['text'] = $news->getText();
        	$form->setData($formData);

	}


	return array('form'=>$form);
    }


   /**
    * Delete existing News entry
    *
    * @return array
    */
    public function deleteAction()
    {

	$user = new Container('user');
        $userId = $user->identity->getId();

        $currentUser = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('id' => $userId));

        $id = $this->getRequest()->getQuery('id');
        if (empty($id)) $id = $this->getRequest()->getPost('id');

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('newsForm');
        $form->setInputFilter(new Form\NewsFilter($this->getObjectManager()));

	$request = $this->getRequest();

        if ($id>0) {

                $news = $this->getObjectManager()->getRepository('Application\Entity\News')->findOneBy(array('id' => $id));
                if (is_object($news)) {


					$activity = new Activity();
                                        $activity->setUserId($userId);
                                        $activity->setDescription('Added News');
                                        $activity->setUri($request->getUriString());
                                        $activity->setQuery(var_export($request->getQuery(),true));
                                        $activity->setPost(var_export($request->getPost(),true));
                                        $activity->setObject(var_export($news,true));
                                        $activity->setCreated(new \DateTime("now"));
                                        $this->getObjectManager()->persist($activity);

                                        $this->getObjectManager()->remove($news);
                                        $this->getObjectManager()->flush();

		}

	}

	return $this->redirect()->toRoute('news');

    }


}
