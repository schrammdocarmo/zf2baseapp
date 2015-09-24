<?php
namespace Application\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as OriginalFormElementErrors;

/**
  * Format form errors
  *
  * @author Christian Schramm do Carmo <christian@schrammdocarmo.com>
  */
class FormElementErrors extends OriginalFormElementErrors  
{
    protected $messageCloseString     = '</p>';
    protected $messageOpenFormat      = '<p class="form-error alert-danger">%s';
    protected $messageSeparatorString = '';
}
