<?php

use OhKannaDuh\UserManager\Requests\CreateUserRequest;

it('uses the expected path', function (string $name, string $job) {
    $request = new CreateUserRequest($name, $job);

    expect($request->getUri()->getPath())->toBe('/api/users');
})->with([
    ['OscarWilde', 'Poet'],
    ['ThaliaBellazecca', 'Guitarist'],
    ['FrodoBaggins', 'Hobbit'],
]);


it('assigns the expected parameters', function (string $name, string $job) {
    $request = new CreateUserRequest($name, $job);

    expect($request->jsonSerialize())->toEqual([
        'uri' => 'https://reqres.in/api/users',
        'method' => 'POST',
        'parameters' => [
            'name' => $name,
            'job' => $job,
        ],
    ]);
})->with([
    ['OscarWilde', 'Poet'],
    ['ThaliaBellazecca', 'Guitarist'],
    ['FrodoBaggins', 'Hobbit'],
]);
