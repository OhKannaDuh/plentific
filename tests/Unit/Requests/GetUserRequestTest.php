<?php

use OhKannaDuh\UserManager\Requests\GetUserRequest;

it('uses the expected path', function (int $id) {
    $request = new GetUserRequest($id);

    expect($request->getUri()->getPath())->toBe('/api/users/' . $id);
})->with([2, 6, 3]);


it('serializes as expected', function (int $id) {
    $request = new GetUserRequest($id);

    expect($request->jsonSerialize())->toEqual([
        'uri' => 'https://reqres.in/api/users/' . $id,
        'method' => 'GET',
        'parameters' => [
            'id' => $id,
        ],
    ]);
})->with([2, 6, 3]);
