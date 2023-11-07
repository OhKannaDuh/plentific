<?php

namespace OhKannaDuh\UserManager\Responses;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

final class CreateUserResponse extends Response
{


    protected static function getValidator(array $payload): Validation
    {
        return  (new Validator())->make($payload, [
            'id' => 'required|integer|min:1',
            'name' => 'required|alpha',
            'job' => 'required|alpha',
            'createdAt' => 'required|date:Y-m-d\TH:i:s.v\Z',
        ]);
    }


    public function getId(): int
    {
        $payload = $this->getPayload();
        return $payload['id'];
    }
}
