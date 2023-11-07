<?php

use OhKannaDuh\UserManager\Api;
use OhKannaDuh\UserManager\Requests\CreateUserRequest;
use OhKannaDuh\UserManager\Requests\GetUserRequest;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;

include __DIR__ . '/vendor/autoload.php';

$api = new Api();

$getUser = new GetUserRequest(2);
$user = $api->getUser($getUser);
// var_dump(json_encode($user, JSON_PRETTY_PRINT));

$userIndex = (new UserIndexRequest())
    ->withPage(2);

$index = $api->getUserIndex($userIndex);
$index->rewind();

foreach ($index as $page) {
    foreach ($page as $item) {
        var_dump($item);
    }
}



$createUser = new CreateUserRequest('Oscar', 'Playwright');
$id = $api->createUser($createUser);
// var_dump($id);
