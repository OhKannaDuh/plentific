<?php

namespace OhKannaDuh\UserManager\Responses;

use OhKannaDuh\UserManager\Resources\User;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

final class UserIndexResponse extends Response
{


    protected static function getValidator(array $payload): Validation
    {
        return  (new Validator())->make($payload, [
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
            'total' => 'required|integer|min:1',
            'total_pages' => 'required|integer|min:1',
            /**
             * This should ideally utilise a general user validator as it is a copy of the validation from
             * GetUserResponse::getValidator.Unsure on the viablity of this as these are nested a level deeper.
             */
            'data.*.id' => 'required|integer|min:1',
            'data.*.email' => 'required|email',
            'data.*.first_name' => 'required|alpha',
            'data.*.last_name' => 'required|alpha',
            'data.*.avatar' => 'required|url',
        ]);
    }


    /**
     * @return iterable<User>
     */
    public function getUsers(): iterable
    {
        $payload = $this->getPayload();
        $data = $payload['data'];

        foreach ($data as $datum) {
            yield (new User())
                ->withId($datum['id'])
                ->withEmail($datum['email'])
                ->withFirstName($datum['first_name'])
                ->withLastName($datum['last_name'])
                ->withAvatar($datum['avatar']);
        }
    }


    public function getTotal(): int
    {
        return $this->getPayload()['total'];
    }


    public function getTotalPages(): int
    {
        return $this->getPayload()['total_pages'];
    }


    public function getPerPage(): int
    {
        return $this->getPayload()['per_page'];
    }


    public function getPage(): int
    {
        return $this->getPayload()['page'];
    }
}
