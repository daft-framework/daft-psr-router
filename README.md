Copyright 2020 SignpostMarv

# daft-psr-router
[![Coverage Status](https://coveralls.io/repos/github/daft-framework/daft-psr-router/badge.svg?branch=master)](https://coveralls.io/github/daft-framework/daft-psr-router?branch=master)
[![Build Status](https://travis-ci.org/daft-framework/daft-psr-router.svg?branch=master)](https://travis-ci.org/daft-framework/daft-psr-router)
[![Type Coverage](https://shepherd.dev/github/daft-framework/daft-psr-router/coverage.svg)](https://shepherd.dev/github/daft-framework/daft-psr-router)

Finally got around to creating a psr-based router. [Because reasons.](https://github.com/daft-framework/daft-router/blob/master/README.md)

Adapts [signpostmarv/daft-router](https://github.com/daft-framework/daft-router) patterns to work with PSR-7, PSR-15, etc.

## differences between daft-router & daft-psr-router

### nikic/fast-route
* the [custom RouteCollector](https://github.com/daft-framework/daft-router/blob/master/src/Router/RouteCollector.php) (which served only to provide type hinting) was dropped.
* the route compiler was simplified, with the instantiation + manipulation methods being dropped.
* the requirement for using `FastRoute\cachedDispatcher` was dropped, although it's use is still recommended
* the custom dispatcher was dropped in favour of a final static method that accepts *any* `FastRoute\Dispatcher` instance, strictly checks the result of `FastRoute\Dispatcher::dispatch()`, and returns an object representing the return value.

### psr-15

* daft-router's request interceptors were retained by adding a specific `Psr\Http\Message\ResponseInterface` instance.
