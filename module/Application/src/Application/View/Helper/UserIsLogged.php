<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

/**
  * View Helper to verify if current user is logged
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class UserIsLogged extends AbstractHelper
{

    public function __construct()
    {
    }

    public function __invoke()
    {
        	$user = new Container('user');

        	if ($user->identity !== null)
          {
        		return true;
        	} else {
        		return false;
        	}

    }

}
