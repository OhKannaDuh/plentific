<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A mock client for spoofing responses from given paths.
 */
final class MockClient extends Client
{
    /** @var array<string,callable(RequestInterface): ResponseInterface> */
    private array $cases = [];

    private array $casesFulfilled = [];


    public function mockCall(string $path, ResponseInterface $response): self
    {
        $clone = clone $this;
        $clone->cases[$path] = function (RequestInterface $request) use ($response): ResponseInterface {
            return $response;
        };
        $clone->casesFulfilled[$path] = false;

        return $clone;
    }


    /**
     * @param string $path
     * @param callable(RequestInterface): ResponseInterface $callback
     *
     * @return MockClient
     */
    public function mockCallWith(string $path, callable $callback): self
    {
        $clone = clone $this;
        $clone->cases[$path] = $callback;
        $clone->casesFulfilled[$path] = false;

        return $clone;
    }


    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if (array_key_exists($path, $this->cases)) {
            $this->casesFulfilled[$path] = true;
            return $this->cases[$path]($request);
        }

        throw new ClientException('Unhandled path: ' . $path, $request, new Response(404));
    }
}
