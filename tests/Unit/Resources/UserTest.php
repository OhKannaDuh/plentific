<?php

use OhKannaDuh\UserManager\Resources\User;

dataset('users', [
    [1, 'oscar@example.com', 'Oscar', 'Wilde', 'https://place-hold.it/128x128'],
    [2, 't_bellazecca@example.com', 'Thalia', 'Bellazecca', 'https://place-hold.it/64x64'],
    [3, 'frodo101@example.com', 'Frodo', 'Baggins', 'https://place-hold.it/128x1'],
]);


it('populates the resource', function (int $id, string $email, string $firstName, string $lastName, string $avatar) {
    $user = (new User())
        ->withId($id)
        ->withEmail($email)
        ->withFirstName($firstName)
        ->withLastName($lastName)
        ->withAvatar($avatar);

    expect($user->getId())->toBe($id);
    expect($user->getEmail())->toBe($email);
    expect($user->getFirstName())->toBe($firstName);
    expect($user->getLastName())->toBe($lastName);
    expect($user->getAvatar())->toBe($avatar);
})->with('users');


it('serialises the resource', function (int $id, string $email, string $firstName, string $lastName, string $avatar) {
    $user = (new User())
        ->withId($id)
        ->withEmail($email)
        ->withFirstName($firstName)
        ->withLastName($lastName)
        ->withAvatar($avatar);

    expect($user->jsonSerialize())->toBe([
        'id' => $id,
        'email' => $email,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'avatar' => $avatar,
    ]);
})->with('users');
