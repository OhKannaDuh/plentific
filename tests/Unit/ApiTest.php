<?php

use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use OhKannaDuh\UserManager\Api;
use OhKannaDuh\UserManager\Exceptions\UserNotFoundException;
use OhKannaDuh\UserManager\Requests\CreateUserRequest;
use OhKannaDuh\UserManager\Requests\GetUserRequest;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tests\Unit\MockClient;

$factory = new HttpFactory();
$client = new MockClient();

it('is constructable with a given client interface', function () use ($client) {
    // Test against instance, because all we really care about is that no exceptions were thrown.
    expect(new Api($client))->toBeInstanceOf(Api::class);
});


it('is constructable with no arguments', function () {
    // Test against instance, because all we really care about is that no exceptions were thrown.
    expect(new Api())->toBeInstanceOf(Api::class);
});

$contents = file_get_contents(__DIR__ . '/data/users.json');
$users = collect(json_decode($contents, true));

$client = $client->mockCall('/api/users/1', $factory->createResponse()->withBody(
    $factory->createStream(json_encode([
        'data' => $users->get(0),
    ])),
));

$client = $client->mockCall('/api/users/5', $factory->createResponse()->withBody(
    $factory->createStream(json_encode([
        'data' => $users->get(4),
    ])),
));

$client = $client->mockCall('/api/users/9', $factory->createResponse()->withBody(
    $factory->createStream(json_encode([
        'data' => $users->get(8),
    ])),
));

$client = $client->mockCallWith('/api/users/99', function (RequestInterface $request): ResponseInterface {
    throw new ClientException('', $request, new Response(404));
});

$client = $client->mockCallWith('/api/users/500', function (RequestInterface $request): ResponseInterface {
    throw new ClientException('Internal Server Error', $request, new Response(500));
});

$client = $client->mockCallWith('/api/users/418', function (RequestInterface $request): ResponseInterface {
    throw new ClientException("I'm a teapot", $request, new Response(418));
});

$client = $client->mockCallWith('/api/users', function (RequestInterface $request) use ($factory, $users): ResponseInterface {
    $method = $request->getMethod();

    // Posting to this endpoint means we want to create a user instead.
    if ($method === 'POST') {
        $payload = json_decode($request->getBody(), true);

        return $factory->createResponse()->withBody(
            $factory->createStream(json_encode([
                'id' => strlen(implode('', $payload)),
                'name' => $payload['name'],
                'job' => $payload['job'],
                'createdAt' => Carbon::now()->format('Y-m-d\TH:i:s.v\Z'),
            ])),
        );
    }


    $parameters = [];
    parse_str($request->getUri()->getQuery(), $parameters);

    $perPage = $parameters['per_page'];
    $pages = $users->chunk($perPage);

    $page = $parameters['page'];
    $pageItems = $pages[$page - 1] ?? new Collection();

    return $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'page' => $page,
            'per_page' => $perPage,
            'total' => count($users),
            'total_pages' => ceil(count($users) / $perPage),
            'data' => $pageItems,
        ])),
    );
});

$api = new Api($client);

// Get User tests
it('Creates a response with the expected user', function (int $id) use ($api) {
    $request = new GetUserRequest($id);

    $user = $api->getUser($request);

    expect($user->getId())->toBe($id);
})->with([1, 5, 9]);


it('throws a user not found expcetion for users that are not found', function () use ($api) {
    $request = new GetUserRequest(99);

    $user = $api->getUser($request);
})->throws(
    UserNotFoundException::class,
    'An error occured while executing this request:' . PHP_EOL .
    '{' . PHP_EOL .
    '    "method": "GET",' . PHP_EOL .
    '    "uri": "https:\/\/reqres.in\/api\/users\/99",' . PHP_EOL .
    '    "parameters": {' . PHP_EOL .
    '        "id": 99' . PHP_EOL .
    '    }' . PHP_EOL .
    '}',
);


it('forwards non 404 excpetions (500)', function () use ($api) {
    $request = new GetUserRequest(500);

    $api->getUser($request);
})->throws(ClientException::class, 'Internal Server Error');

it('forwards non 404 excpetions (418)', function () use ($api) {
    $request = new GetUserRequest(418);

    $api->getUser($request);
})->throws(ClientException::class, "I'm a teapot");


// User index tests
it('Creates a response with the expected page size from a user index request', function (int $perPage) use ($api) {
    $request = (new UserIndexRequest())
        ->withPage(1)
        ->withPerPage($perPage);

    $index = $api->getUserIndex($request);

    expect($index->current()->count())->toBe($perPage);
})->with([2, 6, 3]);

it('Creates a response with the expected page number from a user index request', function (int $page) use ($api) {
    $request = (new UserIndexRequest())
        ->withPage($page)
        ->withPerPage(6);

    $paginator = $api->getUserIndexPaginator($request);

    expect($paginator->currentPage())->toBe($page);
})->with([2, 6, 3]);


// Create user tests
it('Creates a response with the expected id from a create user request', function (string $name, string $job, int $id) use ($api) {
    $request = new CreateUserRequest($name, $job);

    $newId = $api->createUser($request);

    expect($newId)->toBe($id);
})->with([
    // id is generated based on length of name and job
    ['OscarWilde', 'Poet', 14],
    ['ThaliaBellazecca', 'Guitarist', 25],
    ['FrodoBaggins', 'Hobbit', 18],
]);
