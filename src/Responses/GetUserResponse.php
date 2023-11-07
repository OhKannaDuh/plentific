<?php

namespace OhKannaDuh\UserManager\Responses;

use OhKannaDuh\UserManager\Resources\User;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

final class GetUserResponse extends Response
{


    protected static function getValidator(array $payload): Validation
    {
        return  (new Validator())->make($payload, [
            'data.id' => 'required|integer|min:1',
            'data.email' => 'required|email',
            'data.first_name' => 'required|alpha',
            'data.last_name' => 'required|alpha',
            'data.avatar' => 'required|url',
        ]);
    }


    public function getUser(): User
    {
        $payload = $this->getPayload();
        $data = $payload['data'];

        return (new User())
            ->withId($data['id'])
            ->withEmail($data['email'])
            ->withFirstName($data['first_name'])
            ->withLastName($data['last_name'])
            ->withAvatar($data['avatar']);
    }
}
