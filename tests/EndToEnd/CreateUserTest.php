<?php

use OhKannaDuh\UserManager\Api;
use OhKannaDuh\UserManager\Requests\CreateUserRequest;

it('can create a user', function (string $name, string $job) {
    $request = new CreateUserRequest($name, $job);
    $id = (new Api())->createUser($request);

    expect($id)->toBeGreaterThan(0);
})->with([
    ['OscarWilde', 'Poet'],
    ['ThaliaBellazecca', 'Guitarist'],
    ['FrodoBaggins', 'Hobbit'],
]);
