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
