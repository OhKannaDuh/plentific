<?php

namespace OhKannaDuh\UserManager\Requests;

final class CreateUserRequest extends Request
{


    public function __construct(string $name, string $job)
    {
        $this->addParameter('name', $name);
        $this->addParameter('job', $job);

        parent::__construct('POST', 'https://reqres.in/api/users', [
            'Content-Type' => 'application/json'
        ], json_encode([
            'name' => $name,
            'job' => $job,
        ]));
    }
}
