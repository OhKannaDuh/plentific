<?php

use Carbon\Carbon;
use GuzzleHttp\Psr7\HttpFactory;
use OhKannaDuh\UserManager\Responses\CreateUserResponse;

$contents = file_get_contents(__DIR__ . '/../data/users.json');
$users = collect(json_decode($contents, true));

dataset('response', [
    [
        28,
        'OscarWilde',
        'Poet',
        new Carbon('2023-10-17 12:00:13'),
    ],
    [
        53,
        'ThaliaBellazecca',
        'Guitarist',
        new Carbon('1996-01-01 09:13:09'),
    ],
    [
        17,
        'FrodoBaggins',
        'Hobbit',
        new Carbon('2020-02-20 20:20:20'),
    ],
]);


it('produces the expected payload', function (int $id, string $name, string $job, Carbon $createdAt) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'id' => $id,
            'name' => $name,
            'job' => $job,
            'createdAt' => $createdAt->format('Y-m-d\TH:i:s.v\Z'),
        ])),
    );

    $response = CreateUserResponse::fromGuzzleResponse($guzzleResponse);
    expect($response->getPayload())->toBe([
        'id' => $id,
        'name' => $name,
        'job' => $job,
        'createdAt' => $createdAt->format('Y-m-d\TH:i:s.v\Z'),
    ]);
})->with('response');


it('produces the expected id', function (int $id, string $name, string $job, Carbon $createdAt) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'id' => $id,
            'name' => $name,
            'job' => $job,
            'createdAt' => $createdAt->format('Y-m-d\TH:i:s.v\Z'),
        ])),
    );

    $response = CreateUserResponse::fromGuzzleResponse($guzzleResponse);
    expect($response->getId())->toBe($id);
})->with('response');
