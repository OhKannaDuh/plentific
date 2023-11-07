<?php

namespace OhKannaDuh\UserManager\Resources;

use Illuminate\Pagination\LengthAwarePaginator;
use Iterator;
use OhKannaDuh\UserManager\ApiInterface;
use OhKannaDuh\UserManager\Requests\UserIndexRequest;

final class UserIndex implements ResourceInterface, Iterator
{


    /**
     * @param LengthAwarePaginator<User> $current
     */
    public function __construct(
        private LengthAwarePaginator $current,
        private ApiInterface $api
    ) {
    }


    public function jsonSerialize(): array
    {
        return $this->current->toArray();
    }


    /**
     * @return LengthAwarePaginator<User>
     */
    public function current(): LengthAwarePaginator
    {
        return $this->current;
    }


    public function key(): int
    {
        return $this->current->currentPage();
    }


    public function next(): void
    {
        // Get the url of the next page.
        $url = $this->current->nextPageUrl();
        if ($url === null) {
            $total = $this->current->total();
            $perPage = $this->current->perPage();
            $nextPageNumber = $this->current->currentPage() + 1;

            $this->current = new LengthAwarePaginator([], $total, $perPage, $nextPageNumber);
            return;
        }

        $request = $this->getRequest($url);

        $this->current = $this->api->getUserIndexPaginator($request);
    }


    public function rewind(): void
    {
        // Get the url of the first page.
        $url = $this->current->url(1);
        $request = $this->getRequest($url);

        $this->current = $this->api->getUserIndexPaginator($request);
    }


    public function valid(): bool
    {
        $page = $this->current->currentPage();
        $last = $this->current->lastPage();

        return $page > 0 && $page <= $last;
    }


    private function getRequest(string $url): UserIndexRequest
    {
        $components = parse_url($url);
        $query = $components['query'];

        $parameters = [];
        parse_str($query, $parameters);

        return (new UserIndexRequest())
            ->withPerPage($parameters['per_page'] ?? 5)
            ->withPage($parameters['page'] ?? 1);
    }
}
