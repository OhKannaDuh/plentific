<?php

use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OhKannaDuh\UserManager\Api;
use OhKannaDuh\UserManager\Resources\UserIndex;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tests\Unit\MockClient;

$factory = new HttpFactory();
$client = new MockClient();

$contents = file_get_contents(__DIR__ . '/../data/users.json');
$users = collect(json_decode($contents, true));

$client = $client->mockCallWith('/api/users', function (RequestInterface $request) use ($factory, $users): ResponseInterface {
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

it('Creates an index for the expected page.', function (int $perPage, int $page) use ($users, $api) {
    $items = $users->chunk($perPage)[$page - 1] ?? new Collection();
    $paginator = new LengthAwarePaginator($items, $users->count(), $perPage, $page);

    $index = new UserIndex($paginator, $api);

    expect($index->key())->toBe($page);
})->with([
    [3, 2],
    [5, 2],
    [6, 34],
]);

it('Creates an index of the expected size.', function (int $perPage, int $page) use ($users, $api) {
    $items = $users->chunk($perPage)[$page - 1] ?? new Collection();
    $paginator = new LengthAwarePaginator($items, $users->count(), $perPage, $page, [
        'path' => 'https://reqres.in/api/users',
        'query' => [
            'per_page' => $perPage,
        ]
    ]);

    $index = new UserIndex($paginator, $api);
    $data = $index->jsonSerialize();

    expect($data['per_page'])->toBe($perPage);
})->with([
    [3, 2],
    [5, 2],
    [6, 34],
]);

it('returns the current paginator.', function (int $perPage, int $page) use ($users, $api) {
    $items = $users->chunk($perPage)[$page - 1] ?? new Collection();
    $paginator = new LengthAwarePaginator($items, $users->count(), $perPage, $page, [
        'path' => 'https://reqres.in/api/users',
        'query' => [
            'per_page' => $perPage,
        ]
    ]);

    $index = new UserIndex($paginator, $api);

    expect($index->current())->toBe($paginator);
})->with([
    [3, 2],
    [5, 2],
    [6, 34],
]);

it('can be iterated', function (int $perPage, int $totalPages) use ($users, $api) {
    $items = $users->chunk($perPage)[0] ?? new Collection();
    $paginator = new LengthAwarePaginator($items, $users->count(), $perPage, 1, [
        'path' => 'https://reqres.in/api/users',
        'query' => [
            'per_page' => $perPage,
        ]
    ]);

    $index = new UserIndex($paginator, $api);

    expect(iterator_count($index))->toBe($totalPages);
})->with([
    [3, 4],
    [5, 3],
    [6, 2],
]);
