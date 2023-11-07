<?php

namespace OhKannaDuh\UserManager;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Pagination\LengthAwarePaginator;
use OhKannaDuh\UserManager\Exceptions\UserNotFoundException;
use OhKannaDuh\UserManager\Requests\CreateUserRequest;
use OhKannaDuh\UserManager\Requests\GetUserRequest;
use OhKannaDuh\UserManager\Requests\RequestInterface;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;
use OhKannaDuh\UserManager\Resources\User;
use OhKannaDuh\UserManager\Resources\UserIndex;
use OhKannaDuh\UserManager\Responses\CreateUserResponse;
use OhKannaDuh\UserManager\Responses\GetUserResponse;
use OhKannaDuh\UserManager\Responses\UserIndexResponse;
use Psr\Http\Message\ResponseInterface;

final class Api implements ApiInterface
{
    private ClientInterface $client;


    public function __construct(ClientInterface $client = null)
    {
        if ($client === null) {
            $client = new Client();
        }

        $this->client = $client;
    }


    private function send(RequestInterface $request): ResponseInterface
    {
        return $this->client->send($request);
    }


    public function getUser(GetUserRequest $request): User
    {
        try {
            $response = $this->client->send($request);
        } catch (ClientException $e) {
            switch ($e->getCode()) {
                case 404:
                    throw new UserNotFoundException($request);
                default:
                    throw $e;
            }
        }

        $userResponse = GetUserResponse::fromGuzzleResponse($response);

        return $userResponse->getUser();
    }


    public function getUserIndex(UserIndexRequest $request): UserIndex
    {
        $paginator = $this->getUserIndexPaginator($request);
        return new UserIndex($paginator, $this);
    }


    public function getUserIndexPaginator(UserIndexRequest $request): LengthAwarePaginator
    {
        $response = $this->client->send($request);
        $indexResponse = UserIndexResponse::fromGuzzleResponse($response);

        $items = iterator_to_array($indexResponse->getUsers());
        $total = $indexResponse->getTotal();
        $perPage = $indexResponse->getPerPage();
        $page = $indexResponse->getPage();

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => 'https://reqres.in/api/users',
            'query' => [
                'per_page' => $perPage,
            ]
        ]);
    }


    public function createUser(CreateUserRequest $request): int
    {
        $response = $this->send($request);

        $createResponse = CreateUserResponse::fromGuzzleResponse($response);

        return $createResponse->getId();
    }
}
