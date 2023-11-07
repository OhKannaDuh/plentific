<?php

namespace OhKannaDuh\UserManager\Requests;

final class GetUserRequest extends Request
{


    public function __construct(int $id)
    {
        $this->addParameter('id', $id);

        parent::__construct('get', 'https://reqres.in/api/users/' . $id);
    }
}
