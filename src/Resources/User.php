<?php

namespace OhKannaDuh\UserManager\Resources;

final class User implements ResourceInterface
{
    private int $id;

    private string $email;

    private string $firstName;

    private string $lastName;

    private string $avatar;


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'avatar' => $this->avatar,
        ];
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function withId(int $id): User
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function withEmail(string $email): User
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function withFirstName(string $firstName): User
    {
        $clone = clone $this;
        $clone->firstName = $firstName;
        return $clone;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function withLastName(string $lastName): User
    {
        $clone = clone $this;
        $clone->lastName = $lastName;
        return $clone;
    }


    public function getAvatar(): string
    {
        return $this->avatar;
    }


    public function withAvatar(string $avatar): User
    {
        $clone = clone $this;
        $clone->avatar = $avatar;
        return $clone;
    }
}
