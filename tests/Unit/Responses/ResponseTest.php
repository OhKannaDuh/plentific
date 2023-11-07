<?php

namespace Tests\Unit\Responses;

use GuzzleHttp\Psr7\HttpFactory;
use OhKannaDuh\UserManager\Exceptions\ValidationException;
use OhKannaDuh\UserManager\Responses\Response;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

final class TestResponse extends Response
{


    protected static function getValidator(array $payload): Validation
    {
        return  (new Validator())->make($payload, [
            'something' => 'required|integer',
        ]);
    }
}


it('validates incoming payloads', function () {
    $factory = new HttpFactory();
    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([]))
    );

    TestResponse::fromGuzzleResponse($guzzleResponse);
})->throws(
    ValidationException::class,
    'An error occured while validating a response:' . PHP_EOL .
    '{' . PHP_EOL .
    '    "something": {' . PHP_EOL .
    '        "required": "The Something is required"' . PHP_EOL .
    '    }' . PHP_EOL .
    '}'
);


it('validates incoming payload 2', function () {
    $factory = new HttpFactory();
    $guzzleResponse = $factory->createResponse()->withBody(
        $factory->createStream(json_encode([
            'something' => 'not a number',
        ]))
    );

    TestResponse::fromGuzzleResponse($guzzleResponse);
})->throws(
    ValidationException::class,
    'An error occured while validating a response:' . PHP_EOL .
    '{' . PHP_EOL .
    '    "something": {' . PHP_EOL .
    '        "integer": "The Something must be integer"' . PHP_EOL .
    '    }' . PHP_EOL .
    '}'
);
