<?php

use OhKannaDuh\UserManager\Api;
use OhKannaDuh\UserManager\Exceptions\UserNotFoundException;
use OhKannaDuh\UserManager\Requests\GetUserRequest;

it('can get a valid user', function (int $id, string $expectedEmail) {
    $request = new GetUserRequest($id);
    $response = (new Api())->getUser($request);

    expect($response->getEmail())->toBe($expectedEmail);
})->with([
    [1, 'george.bluth@reqres.in'],
    [2, 'janet.weaver@reqres.in'],
    [3, 'emma.wong@reqres.in'],
    [11, 'george.edwards@reqres.in'],
    [12, 'rachel.howell@reqres.in'],
]);


it('throws a `UserNotFoundException` when no user is found', function (int $id) {
    $request = new GetUserRequest($id);
    (new Api())->getUser($request);
})->throws(UserNotFoundException::class)->with([
    -1,
    0,
    13,
    100,
    PHP_INT_MAX,
]);
