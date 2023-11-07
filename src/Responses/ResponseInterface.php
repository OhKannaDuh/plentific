<?php

namespace OhKannaDuh\UserManager\Responses;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface ResponseInterface extends PsrResponseInterface
{


    public function getPayload(): array;
}
