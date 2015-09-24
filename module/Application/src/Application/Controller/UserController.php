<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\User;
use Application\Form;
use Zend\Session\Container;
use Zend\Mail\Message;

use Zend\Mail\Transport\Sendmail as SendmailTransport;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\Console\Request as ConsoleRequest;
use RuntimeException;

/**
  * User login, registration, reset/forgot password handling
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class UserController extends AbstractActionController
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
    * Redirecting default index to login page
    *
    */
    public function indexAction()
    {
	return $this->redirect()->toRoute('login');
    }


   /**
    * Register new User
    *
    * @return array
    */
    public function registerAction()
    {

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('registerForm');
	$form->setInputFilter(new Form\RegisterFilter($this->getObjectManager()));
 
        $request = $this->getRequest();
        if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {


			//STORE USER TO DATABASE

                        $user = new User();
                        $user->setCompany($this->getRequest()->getPost('company'));    
                        $user->setFirstName($this->getRequest()->getPost('first_name'));    
                        $user->setLastName($this->getRequest()->getPost('last_name'));    
                        $user->setEmail($this->getRequest()->getPost('email'));    
                        $user->setPassword(md5($this->getRequest()->getPost('password')));    
                        $user->setStatus(0);
			$token = md5(uniqid(mt_rand(), true));
                        $user->setToken($token);
                        $user->setAddress("");
                        $user->setZipcode("");
                        $user->setCity("");
                        $user->setCountry("");
                        $user->setPhone("");
                        $user->setCreated(new \DateTime("now"));
                        $user->setLastModified(new \DateTime("now"));

                        $this->getObjectManager()->persist($user);
                        $this->getObjectManager()->flush();
                        // $newId = $user->getId();


			//COMPOSE AND SEND ACTIVATION MAIL

			$url = "http://www.example.net/login?token=".$token;

			$translator = $this->getServiceLocator()->get('translator');

			$textContent = sprintf($translator->translate("registration_mail_text"),$url);

			$htmlMarkup = sprintf($translator->translate("registration_mail_html"),$url);

			$text = new MimePart($textContent);
			$text->type = "text/plain";

			$html = new MimePart($htmlMarkup);
			$html->type = "text/html";

			$body = new MimeMessage();
			$body->setParts(array($text, $html));

			$message = new Message();
			$message->addFrom("info@example.net", "example.net")
			        ->addTo($user->getEmail(), $user->getFirstName()." ".$user->getLastName())
			        ->setSubject($translator->translate("registration_mail_subject"));
			$message->setBody($body);
			$message->setEncoding("UTF-8");

			//Debug mail: echo $message->toString();
			
			/*$transport = new SmtpTransport();
			$options   = new SmtpOptions(array(
    			'name'              => 'localhost.localdomain',
    			'host'              => '127.0.0.1',
    			'connection_class'  => 'login',
    			'connection_config' => array(
        			'username' => 'user',
        			'password' => 'pass',
    			),
			));
			$transport->setOptions($options);*/

			$transport = new SendmailTransport();
			$transport->send($message);


			//REDIRECT TO SENT-PAGE
                        return $this->redirect()->toRoute('home');

                }
        }
 
        return array('form' => $form);

    }

   /**
    * User Login
    *
    * @return array
    */
    public function loginAction()
    {

	$formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('loginForm');
 
    	$request = $this->getRequest();

	$user = new Container('user');

	$token = $request->getQuery('token');
	if (!empty($token)) {
		$user->token = $token;
	}

    	if ($request->isPost()) {

		$postData = $request->getPost();
        	$form->setData($postData);
	        if ($form->isValid()) {

			$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
			$adapter = $authService->getAdapter();
			
			$adapter->setIdentityValue($postData['email']);
			$adapter->setCredentialValue(md5($postData['password']));

			$result = $adapter->authenticate();
			if ($result->isValid()) {

				$identity = $result->getIdentity();

				if (!empty($user->token)) {

					$verify = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('token' => $user->token));
					if (is_object($verify)) {
 					 if ($verify->getEmail() == $identity->getEmail() && $verify->getId() == $identity->getId()) {
						$identity->setStatus(1);
						$this->getObjectManager()->persist($identity);
			                        $this->getObjectManager()->flush();
					 }
					}

				}

				$user->identity = $identity;

	                        return $this->redirect()->toRoute('home');

			}

        	}
    	}
 
	return array('form' => $form);

    }

   /**
    * Logout user
    *
    * @return boolean
    */
    public function logoutAction()
    {

	 $session = new Container('user');
	 $session->getManager()->destroy();

         return $this->redirect()->toRoute('home');

    }

   /**
    * User forgot password handling
    *
    * @return array
    */
    public function forgotpwdAction()
    {

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('forgotpwdForm');
	$form->setInputFilter(new Form\ForgotpwdFilter($this->getObjectManager()));

	$request = $this->getRequest();

        if ($request->isPost()) {

                $postData = $request->getPost();
                $form->setData($postData);
                if ($form->isValid()) {

			$user = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('email' => $postData['email']));
                        if (is_object($user)) {

			$url = "http://www.example.net/reset_password?token=".$user->getToken();

			$translator = $this->getServiceLocator()->get('translator');

			$textContent = sprintf($translator->translate("forgotpwd_mail_text"),$url);

                        $htmlMarkup = sprintf($translator->translate("forgotpwd_mail_html"),$url);

                        $text = new MimePart($textContent);
                        $text->type = "text/plain";

                        $html = new MimePart($htmlMarkup);
                        $html->type = "text/html";

                        $body = new MimeMessage();
                        $body->setParts(array($text, $html));

                        $message = new Message();
                        $message->addFrom("info@example.net", "example.net")
                                ->addTo($user->getEmail(), $user->getFirstName()." ".$user->getLastName())
                                ->setSubject($translator->translate("forgotpwd_mail_subject"));
                        $message->setBody($body);
                        $message->setEncoding("UTF-8");

                        //Debug mail: echo $message->toString();
                        
                        /*$transport = new SmtpTransport();
                        $options   = new SmtpOptions(array(
                        'name'              => 'localhost.localdomain',
                        'host'              => '127.0.0.1',
                        'connection_class'  => 'login',
                        'connection_config' => array(
                                'username' => 'user',
                                'password' => 'pass',
                        ),
                        ));
                        $transport->setOptions($options);*/

                        $transport = new SendmailTransport();
                        $transport->send($message);

                        }

			$viewModel = new ViewModel();
			$viewModel->setVariable('email', $user->getEmail());
			return $viewModel;

		}


	}

	return array('form' => $form);

    }

   /**
    * User reset password handling
    *
    * @return array
    */
    public function resetpwdAction()
    {

        $request = $this->getRequest();

        $user = new Container('user');

        $token = $request->getQuery('token');
        if (!empty($token)) {
                $user->token = $token;
        }

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('resetpwdForm');
        $form->setInputFilter(new Form\ResetpwdFilter($this->getObjectManager()));

        $request = $this->getRequest();

        if ($request->isPost()) {

                $postData = $request->getPost();
                $form->setData($postData);
                if ($form->isValid()) {

                        $userObj = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('email' => $postData['email'], 'token' => $user->token));
                        if (is_object($userObj)) {

				$userObj->setPassword(md5($postData['password']));
	                        $userObj->setLastModified(new \DateTime("now"));
	                        $this->getObjectManager()->persist($userObj);
        	                $this->getObjectManager()->flush();

				//$viewModel = new ViewModel();
	                        //$viewModel->setVariable('email', $userObj->getEmail());
        	                //return $viewModel;

				$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                        	$adapter = $authService->getAdapter();
                        
	                        $adapter->setIdentityValue($postData['email']);
        	                $adapter->setCredentialValue(md5($postData['password']));

	                        $result = $adapter->authenticate();
        	                if ($result->isValid()) {

                	                $identity = $result->getIdentity();
					$user->identity = $identity;
	                                return $this->redirect()->toRoute('home');

				} 

			}

		}

	}

	return array('form'=>$form);

    }


}
