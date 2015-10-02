<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Console\Request as ConsoleRequest;
use RuntimeException;

/**
  * Bootstrapping application
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    /**
     * Bootstrap the application
     */
    public function onBootstrap(MvcEvent $e)
    {
        //Set locale
      	$translator = $e->getApplication()->getServiceManager()->get('translator');
      	$translator->setLocale('en');
      	\Zend\Validator\AbstractValidator::setDefaultTranslator($translator);

        //Define view helper routeName in view
      	$e->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('routeName', function($sm) use ($e) {
          	$viewHelper = new View\Helper\RouteName($e->getRouteMatch());
          	return $viewHelper;
      	});

        //Define view helper UserIsLogged in view
      	$e->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('userIsLogged', function($sm) use ($e) {
          	$viewHelper = new View\Helper\UserIsLogged();
          	return $viewHelper;
      	});

        //Handle sessions and ACL
      	$this->bootstrapSession($e);
      	$this->initAcl($e);

        //Attach ACL check to routeStartup
        $eventManager        = $e->getApplication()->getEventManager();
        $eventManager->attach('route', array($this, 'checkAcl'));
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * Retrieve module configuration array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get Autoloader configuration
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Get Controller configurations, injecting ORM into controller
     */
    public function getControllerConfig()
    {
        	return array(
          	 'factories' => array(
            		'Application\Controller\Index' => function ($sm) {
                				$locator = $sm->getServiceLocator();
      				        $controller = new \Application\Controller\IndexController($locator->get('Doctrine\ORM\EntityManager'));
      				        return $controller;
              	},
            		'Application\Controller\Contact' => function ($sm) {
                				$locator = $sm->getServiceLocator();
      				        $controller = new \Application\Controller\ContactController($locator->get('Doctrine\ORM\EntityManager'));
      				        return $controller;
              	},
            		'Application\Controller\User' => function ($sm) {
                				$locator = $sm->getServiceLocator();
      				        $controller = new \Application\Controller\UserController($locator->get('Doctrine\ORM\EntityManager'));
      				        return $controller;
              	},
            		'Application\Controller\Profile' => function ($sm) {
                				$locator = $sm->getServiceLocator();
      				        $controller = new \Application\Controller\ProfileController($locator->get('Doctrine\ORM\EntityManager'));
      				        return $controller;
              	},
            		'Application\Controller\News' => function ($sm) {
                				$locator = $sm->getServiceLocator();
      				        $controller = new \Application\Controller\NewsController($locator->get('Doctrine\ORM\EntityManager'));
      				        return $controller;
              	},
             )
          );
    }

    /**
     * Injecting Zend Translate and Doctrine ORM into Forms, setting filters and hydrators
     */
    public function getFormElementConfig() {
       return array(
            'factories' => array(
                'contactForm' => function($sm) {
                    $form = new Form\ContactForm('contactform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    $form->setInputFilter(new Form\ContactFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'registerForm' => function($sm) {
                    $form = new Form\RegisterForm('registerform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    //$form->setInputFilter(new Form\RegisterFilter); //set in Controller as using Doctrine2
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'forgotpwdForm' => function($sm) {
                    $form = new Form\ForgotpwdForm('forgotpwdform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    //$form->setInputFilter(new Form\ForgotpwdFilter); //set in Controller as using Doctrine2
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'resetpwdForm' => function($sm) {
                    $form = new Form\ResetpwdForm('resetpwdform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    //$form->setInputFilter(new Form\ResetpwdFilter); //set in Controller as using Doctrine2
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'loginForm' => function($sm) {
                    $form = new Form\LoginForm('loginform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    $form->setInputFilter(new Form\LoginFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'profileForm' => function($sm) {
                    $form = new Form\ProfileForm('profileform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    //$form->setInputFilter(new Form\ProfileFilter); //set in Controller as using Doctrine2
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'newsForm' => function($sm) {
                    $form = new Form\NewsForm('newsform', $sm->getServiceLocator()->get('translator'), $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), array());
                    //$form->setInputFilter(new Form\NewsFilter); //set in Controller as using Doctrine2
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
            )
       );
   }

  /**
   * Get Service configuration - authentication via doctrine, session handler config
   */
   public function getServiceConfig()
   {
         return array(
              'factories' => array(
                  'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                      return $serviceManager->get('doctrine.authenticationservice.orm_default');

                  },
      	          'Zend\Session\SessionManager' => function ($sm) {
                          $config = $sm->get('config');
                          if (isset($config['session'])) {
                              $session = $config['session'];

                              $sessionConfig = null;
                              if (isset($session['config'])) {
                                  $class = isset($session['config']['class'])  ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                                  $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                                  $sessionConfig = new $class();
                                  $sessionConfig->setOptions($options);
                              }

                              $sessionStorage = null;
                              if (isset($session['storage'])) {
                                  $class = $session['storage'];
                                  $sessionStorage = new $class();
                              }

                              $sessionSaveHandler = null;
                              if (isset($session['save_handler'])) {
                                  // class should be fetched from service manager since it will require constructor arguments
                                  $sessionSaveHandler = $sm->get($session['save_handler']);
                              }

                              $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                          } else {
                              $sessionManager = new SessionManager();
                          }
                          Container::setDefaultManager($sessionManager);
                          return $sessionManager;
                    },
              )
        );
  }

  /**
   * Session Handling
   */
  public function bootstrapSession($e)
  {
        $session = $e->getApplication()
                     ->getServiceManager()
                     ->get('Zend\Session\SessionManager');
        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init))
        {
            $serviceManager = $e->getApplication()->getServiceManager();
            $request        = $serviceManager->get('Request');

            $session->regenerateId(true);
            $container->init          = 1;

      	    if (php_sapi_name() === 'cli')
            {
            		$container->remoteAddr = "127.0.0.1";
            		$container->httpUserAgent = "PHP Agent";
      	    } else {
                $container->remoteAddr    = $request->getServer()->get('REMOTE_ADDR');
                $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');
      	    }

            $config = $serviceManager->get('Config');
            if (!isset($config['session']))
            {
                return;
            }

            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators']))
            {
                $chain   = $session->getValidatorChain();
                foreach ($sessionConfig['validators'] as $validator)
                {
                    switch ($validator)
                    {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator  = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }
                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }

    /**
     * Handle/Translate form validator error messages
     */
    public function getViewHelperConfig()
    {
      return array(
        'invokables' => array(
            'formelementerrors' => 'Application\Form\View\Helper\FormElementErrors',
        ),
      );
    }

    /**
     * Initiate ACLs
     */
    public function initAcl(MvcEvent $e) {

          $acl = new \Zend\Permissions\Acl\Acl();
          $roles = include dirname(dirname(__DIR__)) . '/config/acl.config.php';

          $allResources = array();
          foreach ($roles as $role => $resources)
          {

              $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
              $acl -> addRole($role);

              $allResources = array_merge($resources, $allResources);

              //adding resources
              foreach ($resources as $resource)
              {
                   if(!$acl ->hasResource($resource))
                   {
                     $acl -> addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
                   }
              }

              //adding restrictions
              foreach ($resources as $resource)
              {
                  $acl -> allow($role, $resource);
              }

          }

          //setting to view
          $e -> getViewModel() -> acl = $acl;
    }


   /**
    * Check if current user has access to current route
    *
    */
   public function checkAcl(MvcEvent $e)
   {
      $route = $e -> getRouteMatch() -> getMatchedRouteName();
      $request = $e->getApplication()->getServiceManager()->get('Request');

      //Role by default
      $userRole = 'Guest';

      //If user is logged in, userRole is LoggedUser
      $user = new Container('user');
      if (isset($user->identity) && is_object($user->identity))
      {
  	     $userRole = 'LoggedUser';
      }

      //If application is run from command line
      if ($request instanceof ConsoleRequest)
      {
          $userRole = 'CLI';
      }

      if ($e->getViewModel()->acl->hasResource($route) && !$e->getViewModel()->acl->isAllowed($userRole, $route))
      {
       //If access denied on console
       if ($request instanceof ConsoleRequest)
       {
          echo "Access denied by ACL\n";
          exit;
       } else {
          //If access denied, redirect to login page
  	      $router = $e->getRouter();
          $response = $e->getResponse();
          $url = $router->assemble(array(), array('name' => 'login'));
          $response->getHeaders()->addHeaderLine('Location', $url);
          $response->setStatusCode(302);
          $e->stopPropagation();
          $response->sendHeaders();
          return $response;
       }
      }
   }


}
