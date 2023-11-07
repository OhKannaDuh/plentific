<?php

use OhKannaDuh\UserManager\Exceptions\ValidationException;
use Rakit\Validation\ErrorBag;

it('has the expected message.', function (ErrorBag $errors, string $expectedMessage) {
    $exception = new ValidationException($errors);

    expect($exception->getMessage())->toBe($expectedMessage);
})->with([
    [
        new ErrorBag([
            'A' => 'B',
        ]),
        'An error occured while validating a response:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "A": "B"' . PHP_EOL .
        '}'
    ],
    [
        new ErrorBag([]),
        'An error occured while validating a response:' . PHP_EOL .
        '[]'
    ],
    [
        new ErrorBag([
            'name' => 'The supplied name was not valid.',
            'age' => 'Age must be greater than 18.'
        ]),
        'An error occured while validating a response:' . PHP_EOL .
        '{' . PHP_EOL .
        '    "name": "The supplied name was not valid.",' . PHP_EOL .
        '    "age": "Age must be greater than 18."' . PHP_EOL .
        '}'
    ],
    [
        new ErrorBag([
            'The supplied name was not valid.',
        ]),
        'An error occured while validating a response:' . PHP_EOL .
        '[' . PHP_EOL .
        '    "The supplied name was not valid."' . PHP_EOL .
        ']'
    ],
]);
