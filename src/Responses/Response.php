<?php

namespace OhKannaDuh\UserManager\Responses;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use OhKannaDuh\UserManager\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Rakit\Validation\Validation;

abstract class Response extends GuzzleResponse implements ResponseInterface
{
    private array $payload = [];


    /**
     * Create a response from a given Guzzle/Psr response.
     *
     * @param PsrResponseInterface $psrResponse
     *
     * @return static
     */
    public static function fromGuzzleResponse(PsrResponseInterface $psrResponse): static
    {
        $payload = json_decode($psrResponse->getBody(), JSON_PRETTY_PRINT);

        $validation = static::getValidator($payload);
        $validation->validate();

        $errors = $validation->errors();
        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $response = new static();
        $response->payload = $payload;
        return $response;
    }


    /**
     * Validate the incoming payload for the given response.
     */
    abstract protected static function getValidator(array $payload): Validation;


    public function getPayload(): array
    {
        return $this->payload;
    }
}
