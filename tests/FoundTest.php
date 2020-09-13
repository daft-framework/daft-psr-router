<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

use function FastRoute\simpleDispatcher;
use function is_subclass_of;
use PHPUnit\Framework\TestCase as Base;

/**
 * @psalm-type THTTP = 'GET'|'POST'|'CONNECT'|'DELETE'|'HEAD'|'OPTIONS'|'PATCH'|'PURGE'|'PUT'|'TRACE'
 */
class FoundTest extends Base
{
	/**
	 * @return list<array{
	 *	0:list<class-string<Source>>,
	 *	1:string,
	 *	2:string,
	 *	3:string,
	 *	4:string,
	 *	5:int
	 * }>
	 */
	final public function foundProvider() : array
	{
		return [
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret',
				'1.1',
				'2.0',
				401,
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret',
				'1.0',
				'1.1',
				401,
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret',
				'2.0',
				'1.0',
				401,
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/',
				'1.0',
				'1.1',
				500,
			],
		];
	}

	/**
	 * @dataProvider foundProvider
	 *
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::ClosureForFilterThatMatchesUri()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::CompileDispatcherArray()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::GenerateRouteCollectorHandler()
	 * @covers \DaftFramework\DaftRouter\Router\Dispatch::Resolve()
	 * @covers \DaftFramework\DaftRouter\Router\Found::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\Found::handle()
	 * @covers \DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier::handle()
	 * @covers \DaftFramework\DaftRouter\Router\MethodNotAllowed::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\MutableRequest::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\MutableRequest::getQueryParams()
	 * @covers \DaftFramework\DaftRouter\Router\MutableRequest::ObtainBaseRequest()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getProtocolVersion()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getStatusCode()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withProtocolVersion()
	 *
	 * @param list<class-string<Source>> $sources
	 */
	final public function test_handle_found(
		array $sources,
		string $method,
		string $path,
		string $request_protocol,
		string $expected_protocol,
		int $expected_status_code
	) : void {
		$handler = Router\Compiler::GenerateRouteCollectorHandler(
			...$sources
		);

		$dispatcher = simpleDispatcher($handler);

		$request = (
			new Fixtures\ServerRequest($path)
		)->withMethod($method)->withProtocolVersion($request_protocol);

		$found = Router\Dispatch::Resolve($dispatcher, $request);

		static::assertInstanceOf(Router\Found::class, $found);

		$response = $found->handle();

		static::assertSame(
			$expected_protocol,
			$response->getProtocolVersion()
		);

		static::assertSame($expected_status_code, $response->getStatusCode());
	}

	/**
	 * @return list<array{
	 *	0:list<class-string<Source>>,
	 *	1:THTTP,
	 *	2:string,
	 *	3:class-string<TypedRoute>,
	 *	4:array<string, string|null>,
	 *	5:class-string<TypedArgs>,
	 *	6:string
	 * }>
	 */
	public function TypedArgsProvider() : array
	{
		return [
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret/1',
				Fixtures\Secret::class,
				['number' => '1'],
				Fixtures\SecretNumber::class,
				'/secret/1',
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/profile/1',
				Fixtures\Profile::class,
				['id' => '1'],
				Fixtures\Unslugged::class,
				'/profile/1',
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/profile/1~foo',
				Fixtures\Profile::class,
				['id' => '1', 'slug' => 'foo'],
				Fixtures\Unslugged::class,
				'/profile/1~foo',
			],
		];
	}

	/**
	 * @dataProvider TypedArgsProvider
	 *
	 * @covers \DaftFramework\DaftRouter\Route::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::ClosureForFilterThatMatchesUri()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::CompileDispatcherArray()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::GenerateRouteCollectorHandler()
	 * @covers \DaftFramework\DaftRouter\Router\Dispatch::Resolve()
	 * @covers \DaftFramework\DaftRouter\Router\Found::__construct()
	 * @covers \DaftFramework\DaftRouter\TypedRoute::__construct()
	 *
	 * @param list<class-string<Source>> $sources
	 * @param THTTP $method
	 * @param class-string<TypedRoute> $expected_route_type
	 * @param array<string, string|null> $expected_args
	 * @param class-string<TypedArgs> $expected_typed_args_type
	 */
	public function test_typed_args(
		array $sources,
		string $method,
		string $path,
		string $expected_route_type,
		array $expected_args,
		string $expected_typed_args_type,
		string $expected_route
	) : void {
		$handler = Router\Compiler::GenerateRouteCollectorHandler(
			...$sources
		);

		$dispatcher = simpleDispatcher($handler);

		$request = (
			new Fixtures\ServerRequest($path)
		)->withMethod($method);

		$found = Router\Dispatch::Resolve($dispatcher, $request);

		static::assertInstanceOf(Router\Found::class, $found);
		static::assertSame($expected_route_type, $found->route);
		static::assertSame($expected_args, $found->args);

		$typed = (
			new $expected_route_type($found->args)
		)->TypedArgsFromUntyped($method);

		static::assertInstanceOf($expected_typed_args_type, $typed);

		static::assertSame(
			$expected_route,
			$expected_route_type::RouteStringFromTypedArgs($typed, $method)
		);
	}

	/**
	 * @return list<array{
	 *	0:list<class-string<Source>>,
	 *	1:THTTP,
	 *	2:string,
	 *	3:class-string<UntypedRoute>,
	 *	4:string
	 * }>
	 */
	public function UntypedRouteProvider() : array
	{
		return [
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret',
				Fixtures\Secret::class,
				'/secret',
			],
		];
	}

	/**
	 * @dataProvider UntypedRouteProvider
	 *
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::ClosureForFilterThatMatchesUri()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::CompileDispatcherArray()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::GenerateRouteCollectorHandler()
	 * @covers \DaftFramework\DaftRouter\Router\Dispatch::Resolve()
	 * @covers \DaftFramework\DaftRouter\Router\Found::__construct()
	 *
	 * @param list<class-string<Source>> $sources
	 * @param THTTP $method
	 * @param class-string<UntypedRoute> $expected_route_type
	 */
	public function test_untyped_route(
		array $sources,
		string $method,
		string $path,
		string $expected_route_type,
		string $expected_route
	) : void {
		$handler = Router\Compiler::GenerateRouteCollectorHandler(
			...$sources
		);

		$dispatcher = simpleDispatcher($handler);

		$request = (
			new Fixtures\ServerRequest($path)
		)->withMethod($method);

		$found = Router\Dispatch::Resolve($dispatcher, $request);

		static::assertInstanceOf(Router\Found::class, $found);
		static::assertSame($expected_route_type, $found->route);
		static::assertSame([], $found->args);

		static::assertSame(
			$expected_route,
			$expected_route_type::RouteStringFromMethod($method)
		);
	}

	/**
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::ClosureForFilterThatMatchesUri()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::CompileDispatcherArray()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::GenerateRouteCollectorHandler()
	 * @covers \DaftFramework\DaftRouter\Router\Dispatch::Resolve()
	 * @covers \DaftFramework\DaftRouter\Router\Found::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\Found::handle()
	 * @covers \DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier::handle()
	 * @covers \DaftFramework\DaftRouter\Router\MutableRequest::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\MutableRequest::getQueryParams()
	 * @covers \DaftFramework\DaftRouter\Router\MutableRequest::ObtainBaseRequest()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getStatusCode()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withProtocolVersion()
	 */
	public function test_conditional_access() : void
	{
		$handler = Router\Compiler::GenerateRouteCollectorHandler(
			Fixtures\BasicSource::class
		);

		$dispatcher = simpleDispatcher($handler);

		$request = new Fixtures\ServerRequest('/secret');

		$found = Router\Dispatch::Resolve($dispatcher, $request);

		static::assertInstanceOf(Router\Found::class, $found);
		static::assertSame(Fixtures\Secret::class, $found->route);
		static::assertSame([], $found->args);

		$response = $found->handle();

		static::assertSame(
			Fixtures\Http401::HTTP_UNAUTHORIZED,
			$response->getStatusCode()
		);

		$request = (
			new Fixtures\ServerRequest('/secret')
		)->withQueryParams([
			'allow-through' => '1',
		]);

		$found = Router\Dispatch::Resolve($dispatcher, $request);

		static::assertInstanceOf(Router\Found::class, $found);
		static::assertSame(Fixtures\Secret::class, $found->route);
		static::assertSame([], $found->args);

		$response = $found->handle();

		static::assertSame(
			Router\RequestNotIntercepted::HTTP_INTERNAL_SERVER_ERROR,
			$response->getStatusCode()
		);
	}
}
