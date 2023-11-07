<?php

namespace OhKannaDuh\UserManager\Exceptions;

use OhKannaDuh\UserManager\Requests\RequestInterface;

/**
 * A base exception for errors that occur during a requests lifetime.
 */
class RequestException extends \RuntimeException
{


    public function __construct(RequestInterface $request)
    {
        $message = "An error occured while executing this request:\n";
        $message .= json_encode($request, JSON_PRETTY_PRINT);

        parent::__construct($message);
    }
}
