<?php

use GuzzleHttp\Psr7\HttpFactory;
use OhKannaDuh\UserManager\Responses\UserIndexResponse;

$contents = file_get_contents(__DIR__ . '/../data/users.json');
$users = collect(json_decode($contents, true));

dataset('response', [
    [1, 2, 12, 6, $users->only(0, 1)->toArray()],
    [3, 2, 12, 6, $users->only(4, 5)->toArray()],
]);


it('produces the expected payload', function (int $page, int $perPage, int $total, int $totalPages, array $data) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'data' => $data,
        ])),
    );

    $response = UserIndexResponse::fromGuzzleResponse($guzzleResponse);

    expect($response->getPayload())->toBe([
        'page' => $page,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => $totalPages,
        'data' => $data
    ]);
})->with('response');


it('produces the expected total', function (int $page, int $perPage, int $total, int $totalPages, array $data) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'data' => $data,
        ])),
    );

    $response = UserIndexResponse::fromGuzzleResponse($guzzleResponse);

    expect($response->getTotal())->toBe($total);
})->with('response');


it('produces the expected total pages', function (int $page, int $perPage, int $total, int $totalPages, array $data) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'data' => $data,
        ])),
    );

    $response = UserIndexResponse::fromGuzzleResponse($guzzleResponse);

    expect($response->getTotalPages())->toBe($totalPages);
})->with('response');


it('produces the expected per page', function (int $page, int $perPage, int $total, int $totalPages, array $data) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'data' => $data,
        ])),
    );

    $response = UserIndexResponse::fromGuzzleResponse($guzzleResponse);

    expect($response->getPerPage())->toBe($perPage);
})->with('response');


it('produces the expected page', function (int $page, int $perPage, int $total, int $totalPages, array $data) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'data' => $data,
        ])),
    );

    $response = UserIndexResponse::fromGuzzleResponse($guzzleResponse);

    expect($response->getPage())->toBe($page);
})->with('response');
