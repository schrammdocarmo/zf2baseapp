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
use Zend\Crypt\Password\Bcrypt;

/**
 * User login, registration, reset/forgot password handling
 * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
 */
class UserController extends BaseController
{

    /**
     * Redirecting default index to login page
     */
    public function indexAction()
    {
	     return $this->redirect()->toRoute('login');
    }


    /**
     * Register new User
     * @return array
     */
    public function registerAction()
    {

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('registerForm');
	      $form->setInputFilter(new Form\RegisterFilter($this->getObjectManager()));

        $request = $this->getRequest();
        if ($request->isPost())
        {
                $form->setData($request->getPost());
                if ($form->isValid())
                {

			                  //CREATE NEW USER
                        $user = new User();
                        $user->setCompany($this->getRequest()->getPost('company'));
                        $user->setFirstName($this->getRequest()->getPost('first_name'));
                        $user->setLastName($this->getRequest()->getPost('last_name'));
                        $user->setEmail($this->getRequest()->getPost('email'));
			                  $bcrypt = new Bcrypt();
			                  $securePass = $bcrypt->create($this->getRequest()->getPost('password'));
                        $user->setPassword($securePass);
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

                        //AND SAVE USER TO DB
                        $this->getObjectManager()->persist($user);
                        $this->getObjectManager()->flush();
                        // $newId = $user->getId();


			                  //COMPOSE AND SEND ACTIVATION MAIL
                        //@TODO move hostname/baseurl to config
			                  $url = "http://www.example.net/login?token=".$token;
                        $translator = $this->getServiceLocator()->get('translator');

                        //Create text part of mail
                        $textContent = sprintf($translator->translate("registration_mail_text"),$url);
                  			$text = new MimePart($textContent);
                  			$text->type = "text/plain";

                        //Create html part of mail
                        $htmlMarkup = sprintf($translator->translate("registration_mail_html"),$url);
                  			$html = new MimePart($htmlMarkup);
                  			$html->type = "text/html";

                        //Compose mail
                  			$body = new MimeMessage();
                  			$body->setParts(array($text, $html));
                  			$message = new Message();
                        //@TODO move email address etc. to config
                  			$message->addFrom("info@example.net", "example.net")
                  			        ->addTo($user->getEmail(), $user->getFirstName()." ".$user->getLastName())
                  			        ->setSubject($translator->translate("registration_mail_subject"));
                  			$message->setBody($body);
                  			$message->setEncoding("UTF-8");
			                  //Debug mail: echo $message->toString();

                        //Depending on how you send mail..
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

                        //Send email
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
     * @return array
     */
    public function loginAction()
    {

        //Create login form
	      $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('loginForm');

        //Get request object
    	  $request = $this->getRequest();

        //Get user object from session
       	$user = new Container('user');

        //If token is in url, then set it
      	$token = $request->getQuery('token');
      	if (!empty($token))
        {
      		$user->token = $token;
      	}

    	  if ($request->isPost())
        {

		      $postData = $request->getPost();
        	$form->setData($postData);
	        if ($form->isValid())
          {

          			$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
          			$adapter = $authService->getAdapter();

          			$adapter->setIdentityValue($postData['email']);

          			$currentUser = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('email' => $postData['email']));

          			$bcrypt = new Bcrypt();
          			if ($bcrypt->verify($postData['password'], $currentUser->getPassword()))
                {
          				$adapter->setCredentialValue($currentUser->getPassword());
          			} else {
          				//Just not to throw an exception
          				$adapter->setCredentialValue($postData['password']);
          			}

          			unset($currentUser);

          			$result = $adapter->authenticate();
                //If login credentials are valid
          			if ($result->isValid())
                {

                      //Get identity from DB result
          				    $identity = $result->getIdentity();

              				if (!empty($user->token))
                      {
              					$verify = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('token' => $user->token));
              					if (is_object($verify))
                        {
               					 if ($verify->getEmail() == $identity->getEmail() && $verify->getId() == $identity->getId())
                         {
              						$identity->setStatus(1);
              						$this->getObjectManager()->persist($identity);
              			                        $this->getObjectManager()->flush();
              					 }
              					}
              				}

                      //Set identity, user is logged
              				$user->identity = $identity;

                      //Login successful, return to index page
                      return $this->redirect()->toRoute('home');
          			}

        	} //if valid

    	} //if is post

	    return array('form' => $form);

    }


    /**
     * Logout user
     * @return boolean
     */
    public function logoutAction()
    {
         //Destroy current session
      	 $session = new Container('user');
      	 $session->getManager()->destroy();

         //Redirect to index page
         return $this->redirect()->toRoute('home');
    }

    /**
     * User forgot password handling
     * @return array
     */
    public function forgotpwdAction()
    {

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('forgotpwdForm');
	      $form->setInputFilter(new Form\ForgotpwdFilter($this->getObjectManager()));

	      $request = $this->getRequest();

        if ($request->isPost())
        {

                $postData = $request->getPost();
                $form->setData($postData);
                if ($form->isValid())
                {

			                  $user = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('email' => $postData['email']));
                        if (is_object($user))
                        {
                                //@TODO hostname/baseurl to config
			                          $url = "http://www.example.net/reset_password?token=".$user->getToken();

			                          $translator = $this->getServiceLocator()->get('translator');

                                //Create text part of email
			                          $textContent = sprintf($translator->translate("forgotpwd_mail_text"),$url);
                                $text = new MimePart($textContent);
                                $text->type = "text/plain";

                                //Create html part of email
                                $htmlMarkup = sprintf($translator->translate("forgotpwd_mail_html"),$url);
                                $html = new MimePart($htmlMarkup);
                                $html->type = "text/html";

                                //Create email
                                $body = new MimeMessage();
                                $body->setParts(array($text, $html));
                                $message = new Message();
                                //@TODO move email address to config
                                $message->addFrom("info@example.net", "example.net")
                                        ->addTo($user->getEmail(), $user->getFirstName()." ".$user->getLastName())
                                        ->setSubject($translator->translate("forgotpwd_mail_subject"));
                                $message->setBody($body);
                                $message->setEncoding("UTF-8");
                                //Debug mail: echo $message->toString();

                                //Depending on how you send mail
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

                                //Actually send mail
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
     * @return array
     */
    public function resetpwdAction()
    {
        //Get request object
        $request = $this->getRequest();

        //Get user object from session
        $user = new Container('user');

        //If token in url, then set it
        $token = $request->getQuery('token');
        if (!empty($token))
        {
                $user->token = $token;
        }

        //Create form
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('resetpwdForm');
        $form->setInputFilter(new Form\ResetpwdFilter($this->getObjectManager()));

        if ($request->isPost())
        {

                $postData = $request->getPost();
                $form->setData($postData);
                if ($form->isValid())
                {

                        $userObj = $this->getObjectManager()->getRepository('Application\Entity\User')->findOneBy(array('email' => $postData['email'], 'token' => $user->token));
                        if (is_object($userObj))
                        {

                               //Update password
	                             $bcrypt = new Bcrypt();
        	                     $securePass = $bcrypt->create($postData['password']);
				                       $userObj->setPassword($securePass);

                               //Update last modification date and save to DB
    	                         $userObj->setLastModified(new \DateTime("now"));
    	                         $this->getObjectManager()->persist($userObj);
            	                 $this->getObjectManager()->flush();

                               //Login user wit new credentials
				                       $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                        	     $adapter = $authService->getAdapter();
      	                       $adapter->setIdentityValue($postData['email']);
              	               $adapter->setCredentialValue($securePass);
	                             $result = $adapter->authenticate();
        	                     if ($result->isValid())
                               {
                	                 $identity = $result->getIdentity();
					                         $user->identity = $identity;
                                   //and redirect to index page
	                                 return $this->redirect()->toRoute('home');
				                       }

			                  }

		              }

	        }

	        return array('form'=>$form);
    }



}
