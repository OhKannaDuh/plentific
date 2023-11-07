<?php

namespace OhKannaDuh\UserManager\Exceptions;

use Rakit\Validation\ErrorBag;

/**
 * An exception for when a response body fails to validate.
 */
class ValidationException extends \UnexpectedValueException
{


    public function __construct(ErrorBag $errors)
    {
        $message = "An error occured while validating a response:\n";
        $message .= json_encode($errors->toArray(), JSON_PRETTY_PRINT);

        parent::__construct($message);
    }
}
