<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Contact;
use Zend\Session\Container;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
  * Contact Form, validation, sending mail
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class ContactController extends BaseController
{


   /**
    * Contact form, validate and send mail
    * @return array
    */
    public function indexAction()
    {

	      $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('contactForm');

	      $session = new Container('user');
        if (isset($session->identity))
        {
                $user = $session->identity;
                if (is_object($user))
                {
              			     $userData = array('company' => $user->getCompany(),
              					  'first_name' => $user->getFirstName(),
              					  'last_name' => $user->getLastName(),
              					 );
                         $form->setData($userData);
                }
        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
        	    $form->setData($request->getPost());
	            if ($form->isValid())
              {

        		        $contact = new Contact();
              			if (is_object($user))
                    {
                    				$contact->setCompany($user->getCompany());
                    				$contact->setFirstName($user->getFirstName());
                    				$contact->setLastName($user->getLastName());
              			} else {
                            $contact->setCompany($this->getRequest()->getPost('company'));
                            $contact->setFirstName($this->getRequest()->getPost('first_name'));
                  	        $contact->setLastName($this->getRequest()->getPost('last_name'));
              			}

  	                $contact->setMessage($this->getRequest()->getPost('message'));
  	                $contact->setUserId(0);
              			$contact->setStatus(0);
              			$contact->setCreated(new \DateTime("now"));
              			$contact->setLastModified(new \DateTime("now"));

			              $this->getObjectManager()->persist($contact);
	                  $this->getObjectManager()->flush();
           		      // $newId = $user->getId();

                    $translator = $this->getServiceLocator()->get('translator');

                    $textContent = sprintf($translator->translate("contact_mail_text"),$contact->getCompany(),$contact->getFirstName(),$contact->getLastName(),$contact->getMessage());

                    $htmlMarkup = sprintf($translator->translate("contact_mail_html"),$contact->getCompany(),$contact->getFirstName(),$contact->getLastName(),$contact->getMessage());

                    $text = new MimePart($textContent);
                    $text->type = "text/plain";

                    $html = new MimePart($htmlMarkup);
                    $html->type = "text/html";

                    $body = new MimeMessage();
                    $body->setParts(array($text, $html));

                    $message = new Message();
                    $message->addFrom("info@example.net", "example.net")
                            ->addTo("info@example.net", "example.net")
                            ->setSubject($translator->translate("contact_mail_subject"));
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

	                  return $this->redirect()->toRoute('home');

        	      }
    	    }

    	    return array('form' => $form);

    }

}
