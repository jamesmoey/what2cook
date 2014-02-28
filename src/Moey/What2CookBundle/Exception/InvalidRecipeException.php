<?php
namespace Moey\What2CookBundle\Exception;

use Exception;

class InvalidRecipeException extends \RuntimeException {
    public function __construct($recipeName, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($recipeName . $message, $code, $previous);
    }

} 