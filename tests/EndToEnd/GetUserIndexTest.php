<?php

use OhKannaDuh\UserManager\Api;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;

it('can get a valid user index', function (int $page, int $perPage, int $expectedTotalPages) {
    $request = (new UserIndexRequest())
        ->withPage($page)
        ->withPerPage($perPage);

    $response = (new Api())->getUserIndex($request);

    expect($response->current()->currentPage())->toBe($page);
    expect($response->current()->perPage())->toBe($perPage);
    expect($response->current()->lastPage())->toBe($expectedTotalPages);
    expect($response->current()->count())->toBe(min($response->current()->total(), $perPage));
    expect($response->valid())->toBe(true);
})->with([
    [1, 2, 6],
    [2, 6, 2],
    [3, 3, 4],
    [1, 24, 1],
]);


it('can identify an invalid page.', function (int $page) {
    $request = (new UserIndexRequest())
        ->withPage($page);

    $response = (new Api())->getUserIndex($request);

    expect($response->current()->currentPage())->toBe($page);
    expect($response->current()->count())->toBe(0);
    expect($response->valid())->toBe(false);
})->with([
    /**
     * The LengthAwarePaginator automatically changes this to one if it is below
     * one, so we can't test those cases. (-1, 0 etc.)
     */
    3,
    100,
]);
