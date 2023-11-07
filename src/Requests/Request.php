<?php

namespace OhKannaDuh\UserManager\Requests;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

abstract class Request extends GuzzleRequest implements RequestInterface
{
    /** @var array<string,string|int> */
    private $parameters = [];


    protected function addParameter(string $key, string|int $value): void
    {
        $this->parameters[$key] = $value;
    }


    protected function getParameters(): array
    {
        return $this->parameters;
    }


    public function jsonSerialize(): array
    {
        return [
            'method' => $this->getMethod(),
            'uri' => (string) $this->getUri(),
            'parameters' => $this->parameters,
        ];
    }
}
