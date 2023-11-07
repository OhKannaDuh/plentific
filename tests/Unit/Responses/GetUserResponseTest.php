<?php

use GuzzleHttp\Psr7\HttpFactory;
use OhKannaDuh\UserManager\Responses\GetUserResponse;

$contents = file_get_contents(__DIR__ . '/../data/users.json');
$users = collect(json_decode($contents, true));

dataset('response', [
    [$users->get(1)],
    [$users->get(4)],
    [$users->get(7)],
]);


it('produces the expected payload', function (array $user) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'data' => $user,
        ])),
    );

    $response = GetUserResponse::fromGuzzleResponse($guzzleResponse);

    expect($response->getPayload())->toBe([
        'data' => $user
    ]);
})->with('response');


it('produces the expected User', function (array $user) {
    $factory = new HttpFactory();

    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'data' => $user,
        ])),
    );

    $response = GetUserResponse::fromGuzzleResponse($guzzleResponse);

    $payload = $response->getPayload();
    $user = $response->getUser();

    expect($user->getId())->toBe($payload['data']['id']);
    expect($user->getEmail())->toBe($payload['data']['email']);
    expect($user->getFirstName())->toBe($payload['data']['first_name']);
    expect($user->getLastName())->toBe($payload['data']['last_name']);
    expect($user->getAvatar())->toBe($payload['data']['avatar']);
})->with('response');
