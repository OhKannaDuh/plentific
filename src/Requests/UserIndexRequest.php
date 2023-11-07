<?php

namespace OhKannaDuh\UserManager\Requests;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

final class UserIndexRequest extends Request
{


    public function __construct()
    {
        parent::__construct('get', 'https://reqres.in/api/users');
    }


    /**
     * Get the relevant Uri based on the currently supplied parameters.
     */
    private function getIndexUri(): UriInterface
    {
        $query = [];
        $parameters = $this->getParameters();

        // Ensure we only include the parameters we expect to make up this uri.
        if (array_key_exists('perPage', $parameters)) {
            $query['per_page'] = $parameters['perPage'];
        }

        if (array_key_exists('page', $parameters)) {
            $query['page'] = $parameters['page'];
        }

        return  new Uri('https://reqres.in/api/users?' . http_build_query($query));
    }


    public function withPerPage(int $perPage): UserIndexRequest
    {
        $this->addParameter('perPage', $perPage);
        $uri = $this->getIndexUri();

        return $this->withUri($uri);
    }


    public function getPerPage(): int|null
    {
        return $this->getParameters()['perPage'] ?? null;
    }


    public function withPage(int $page): UserIndexRequest
    {
        $this->addParameter('page', $page);
        $uri = $this->getIndexUri();

        return $this->withUri($uri);
    }


    public function getPage(): int|null
    {
        return $this->getParameters()['page'] ?? null;
    }
}
