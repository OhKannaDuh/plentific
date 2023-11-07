<?php

use OhKannaDuh\UserManager\Exceptions\RequestException;
use OhKannaDuh\UserManager\Requests\CreateUserRequest;
use OhKannaDuh\UserManager\Requests\GetUserRequest;
use OhKannaDuh\UserManager\Requests\RequestInterface;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;

it('has the expected message.', function (RequestInterface $request, string $expectedMessage) {
    $exception = new RequestException($request);

    expect($exception->getMessage())->toBe($expectedMessage);
})->with([
    [
        new GetUserRequest(1),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users\/1",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "id": 1' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        new GetUserRequest(4),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users\/4",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "id": 4' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        new UserIndexRequest(),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users",' . PHP_EOL .
        '    "parameters": []' . PHP_EOL .
        '}',
    ],
    [
        (new UserIndexRequest())
            ->withPage(2),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users?page=2",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "page": 2' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        (new UserIndexRequest())
            ->withPage(12),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users?page=12",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "page": 12' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        (new UserIndexRequest())
            ->withPerPage(8),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users?per_page=8",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "perPage": 8' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        (new UserIndexRequest())
            ->withPerPage(709),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users?per_page=709",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "perPage": 709' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        (new UserIndexRequest())
            ->withPage(2)
            ->withPerPage(5),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users?per_page=5&page=2",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "page": 2,' . PHP_EOL .
        '        "perPage": 5' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        (new UserIndexRequest())
            ->withPerPage(12)
            ->withPage(43),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "GET",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users?per_page=12&page=43",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "perPage": 12,' . PHP_EOL .
        '        "page": 43' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        new CreateUserRequest('OscarWilde', 'Poet'),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "POST",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "name": "OscarWilde",' . PHP_EOL .
        '        "job": "Poet"' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        new CreateUserRequest('ThaliaBellazecca', 'Guitarist'),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "POST",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "name": "ThaliaBellazecca",' . PHP_EOL .
        '        "job": "Guitarist"' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
    [
        new CreateUserRequest('FrodoBaggins', 'Hobbit'),
        'An error occured while executing this request:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "method": "POST",' . PHP_EOL .
        '    "uri": "https:\/\/reqres.in\/api\/users",' . PHP_EOL .
        '    "parameters": {' . PHP_EOL .
        '        "name": "FrodoBaggins",' . PHP_EOL .
        '        "job": "Hobbit"' . PHP_EOL .
        '    }' . PHP_EOL .
        '}',
    ],
]);
