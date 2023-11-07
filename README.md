# Getting started

Simply build and run the container with docker to get started:

```
docker-compose up -d
docker-compose exec users-cli bash
```

This should launch you into a cli session, so let's install our dependency libraries.

```
composer install
```

# Tests

There are 2 test suites included here, end-to-end and unit. The End-to-end tests interact with the actual live API and the unit tests utilize a mock client that provides responses based on defined requests.

## End to End tests

Tests that include hitting live endpoints and shouldn't be relied upon for automated builds, but to ensure the library works as expected against the current version.

```
./vendor/bin/pest --testsuite e2e
```

## Unit tests

Tests that the library works against a set of mock data and expectations.

```
./vendor/bin/pest --testsuite unit
```

## With coverage

The docker container includes the pcov extension for generating code coverage, pest comes with several coverage-generating options. I prefer `coverage-html`:

```
./vendor/bin/pest --testsuite unit --coverage-html tests/coverage
```

You can check out other options with:

```
./vendor/bin/pest --help
```

or by checking out the documentation https://pestphp.com/docs/test-coverage

# Pest

This was the first time I had used pest in any real capacity, and so far I think I prefer phpunit and its class-based structure. They may be more elegant ways to structure pest tests and files but that's something to look into in the future.

There are also some things I didn't directly test due to time constraints, mainly I didn't do any robust unit tests on the response validation, however, I didn't want to delay this any further and decided what is already there is more than enough for technical demonstration.

# Exceptions

I decided to let most of the exceptions propagate that weren't mentioned in the reqres documentation. Which as far as the spec required, was only the 404 response on the get user endpoint. I could have gone a step further and targeted other common response codes, the 500s spring to mind here, I could have added a try/catch to the Api classes send method for this. In the end, I opted to let any potential user handle these as required, as the Client Exceptions should provide all the information needed.

# Packages used

I used a few packages to help with the development of this library.

## Guzzle

The main library used was Guzzle, and I think this one is pretty self-explanatory. It's one of if not the most popular HTTP clients for PHP and my go-to.

## Collections and Pagination

I opted to use illuminates collection and pagination libraries for the index/list portion of this library.

## Validation

I decided not not use illuminates validation library and instead used `rakit/validation` https://github.com/rakit/validation. Which is a library designed to mimic the design of illuminates validation library. I used this of the former because It was much less involved to get set up. Illuminates validation unit requires the use of translators which I felt was superfluous here, not a bad feature by any means, just not necessary for this test.

# PHPCS

I don't have much to say about this, just a nice little tool for ensuring coding standards are maintained, with less effort from the developer.

# Other tools

These are some other tools I usually include in other projects but didn't here due to time constraints.

- PHPStan https://phpstan.org/

- Infection https://infection.github.io/

# Design

I just wanted to make some remarks on the design of this library.

## The flow

This is pretty simple, you create an instance of the API class and send it specified request objects for the different calls, which were get user, get paginated user index, and create user. I would have had these return the responses directly and allowed the user to work with it as they require, but that would have gone against spec.

## The requests

You instantiate a request with their required parameters, which except the `UserIndexRequest` is all of them. The `UserIndexRequest` allows two optional parameters `page` and `per_page`, which allow the user to manipulate the request as needed. I think I would change `UserIndexRequest` to `GetUserIndexRequest` so it matches the other requests if I were to make more changes here.

## The responses

I'm pretty with how the responses came out. We promote a response we get from Guzzle into a specified response for a given request. i.e. A response for `GetUserRequest` will be promoted to a `GetUserResponse`. This allows us to validate the data on a case-by-case basis, in an extensible way.

One potential issue here is that if the body in the response isn't valid JSON, then we'd get a runtime error because a null is provided when an array is expected. This would be very easy to fix, we could just make this an array if it becomes null after parsing the JSON. Then the validator would pick up and report on the missing data.

## The API

I don't have much to say about this, it exposes a few methods that require specific request types to call. This ensures we get the data we need for a given endpoint/call. I already touched on this but I would have had these return the responses directly, to allow more freedom with data. In that case, the logic that creates the paginator in `Api::getUserIndexPaginator` would have been moved to the response class.
