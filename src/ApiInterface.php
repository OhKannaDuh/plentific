<?php

namespace OhKannaDuh\UserManager;

use Illuminate\Pagination\LengthAwarePaginator;
use OhKannaDuh\UserManager\Requests\CreateUserRequest;
use OhKannaDuh\UserManager\Requests\GetUserRequest;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;
use OhKannaDuh\UserManager\Resources\User;
use OhKannaDuh\UserManager\Resources\UserIndex;

interface ApiInterface
{


    /**
     * Get a User from a GetUserRequest.
     */
    public function getUser(GetUserRequest $request): User;


    /**
     * Get a UserIndex from a UserIndexRequest.
     */
    public function getUserIndex(UserIndexRequest $request): UserIndex;


    /**
     * Get a the LengthAwarePaginatorx from a UserIndexRequest.
     */
    public function getUserIndexPaginator(UserIndexRequest $request): LengthAwarePaginator;


    /**
     * Create a user from a CreateUserRequest and get its id.
     */
    public function createUser(CreateUserRequest $request): int;
}
