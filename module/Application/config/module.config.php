<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 *
 *
 * Applicatino routes and other module configuration
 *
 * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'contact' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Contact',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'profile' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/profile',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Profile',
                        'action'     => 'index',
                    ),
                ),
            ),
            'register' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/register',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'register',
                    ),
                ),
            ),
            'forgotpwd' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/forgot_password',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'forgotpwd',
                    ),
                ),
            ),
            'resetpwd' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/reset_password',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'resetpwd',
                    ),
                ),
            ),
            'news' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/news',
                    'defaults' => array(
                        'controller' => 'Application\Controller\News',
                        'action'     => 'index',
                    ),
                ),
            ),
            'news_edit' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/news_edit',
                    'defaults' => array(
                        'controller' => 'Application\Controller\News',
                        'action'     => 'edit',
                    ),
                ),
            ),
            'news_add' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/news_add',
                    'defaults' => array(
                        'controller' => 'Application\Controller\News',
                        'action'     => 'add',
                    ),
                ),
            ),
            'news_delete' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/news_delete',
                    'defaults' => array(
                        'controller' => 'Application\Controller\News',
                        'action'     => 'delete',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en',
        'translation_file_patterns' => array(
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            //'Application\Controller\Index' => 'Application\Controller\IndexController',
            //'Application\Controller\Contact' => 'Application\Controller\ContactController',
            //'Application\Controller\User' => 'Application\Controller\UserController',
            //'Application\Controller\Profile' => 'Application\Controller\ProfileController',
            //'Application\Controller\News' => 'Application\Controller\NewsController',
        ),
    ),
    'controller_plugins' => array(
    'invokables' => array(
     )
    ),
   'form_elements' => array(
    ),
    'forms' => array(
        'invokables' => array(
            'Application\Form\ContactForm' => 'Application\Form\ContactForm',
            'Application\Form\ContactFilter' => 'Application\Form\ContactFilter',
            'Application\Form\LoginForm' => 'Application\Form\LoginForm',
            'Application\Form\LoginFilter' => 'Application\Form\LoginFilter',
            'Application\Form\RegisterForm' => 'Application\Form\RegisterForm',
            'Application\Form\RegisterFilter' => 'Application\Form\RegisterFilter',
            'Application\Form\ForgotpwdForm' => 'Application\Form\ForgotpwdForm',
            'Application\Form\ForgotpwdFilter' => 'Application\Form\ForgotpwdFilter',
            'Application\Form\ResetpwdForm' => 'Application\Form\ResetpwdForm',
            'Application\Form\ResetpwdFilter' => 'Application\Form\ResetpwdFilter',
            'Application\Form\ProfileForm' => 'Application\Form\ProfileForm',
            'Application\Form\ProfileFilter' => 'Application\Form\ProfileFilter',

            'Application\Form\NewsForm' => 'Application\Form\NewsForm',
            'Application\Form\NewsFilter' => 'Application\Form\NewsFilter',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
	'authentication' => array(
        	'orm_default' => array(
            		'object_manager' => 'Doctrine\ORM\EntityManager',
            		'identity_class' => 'Application\Entity\User',
            		'identity_property' => 'email',
            		'credential_property' => 'password',
        	),
    	),
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            )
        )
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'example_net',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
    ),

);
