<?php

use OhKannaDuh\UserManager\Requests\UserIndexRequest;

it('uses the expected path', function (int $perPage, int $page) {
    $request = (new UserIndexRequest())
        ->withPerPage($perPage)
        ->withPage($page);

    $uri = $request->getUri();

    expect($uri->getPath())->toBe('/api/users');
    expect($uri->getQuery())->toBe("per_page={$perPage}&page={$page}");
})->with([
    [1, 5],
    [3, 2],
    [14, 81],
]);


it('serializes as expected', function (int $perPage, int $page) {
    $request = (new UserIndexRequest())
        ->withPerPage($perPage)
        ->withPage($page);

    expect($request->jsonSerialize())->toEqual([
        'uri' => "https://reqres.in/api/users?per_page={$perPage}&page={$page}",
        'method' => 'GET',
        'parameters' => [
            'perPage' => $perPage,
            'page' => $page,
        ],
    ]);
})->with([
    [1, 5],
    [3, 2],
    [14, 81],
]);


it('to have null parameters by default', function () {
    $request = new UserIndexRequest();

    expect($request->getPerPage())->toBeNull();
    expect($request->getPage())->toBeNull();
});

it('to assign per page and page parameters', function (int $perPage, int $page) {
    $request = (new UserIndexRequest())
        ->withPerPage($perPage)
        ->withPage($page);

    expect($request->getPerPage())->toBe($perPage);
    expect($request->getPage())->toBe($page);
})->with([
    [1, 5],
    [3, 2],
    [14, 81],
]);
