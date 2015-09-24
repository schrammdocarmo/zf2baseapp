<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
  * View Helper to return current route
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class RouteName extends AbstractHelper
{

    protected $routeMatch;

    public function __construct($routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    public function __invoke()
    {
        if ($this->routeMatch) {
            return $this->routeMatch->getMatchedRouteName();
        }
    }
}
