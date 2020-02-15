<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use PHPUnit\Framework\TestCase as Base;
use UnexpectedValueException;

class DispatchTest extends Base
{
	/**
	 * @return list<array{
	 *	0:list<class-string<Source>>,
	 *	1:string,
	 *	2:string,
	 *	3:class-string<Router\NotFound>|class-string<Router\MethodNotAllowed>|class-string<Router\Found>
	 * }>
	 */
	public function GenerateRouteCollectorHandlerProvider() : array
	{
		return [
			[[Fixtures\BasicSource::class], 'GET', '/', Router\Found::class],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret',
				Router\Found::class,
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/secret/1',
				Router\Found::class,
			],
			[
				[Fixtures\BasicSource::class],
				'POST',
				'/',
				Router\MethodNotAllowed::class,
			],
			[
				[Fixtures\BasicSource::class],
				'GET',
				'/foo/bar/baz',
				Router\NotFound::class,
			],
		];
	}

	/**
	 * @dataProvider GenerateRouteCollectorHandlerProvider
	 *
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::ClosureForFilterThatMatchesUri()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::CompileDispatcherArray()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::GenerateRouteCollectorHandler()
	 * @covers \DaftFramework\DaftRouter\Router\Dispatch::Resolve()
	 * @covers \DaftFramework\DaftRouter\Router\Found::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\MethodNotAllowed::__construct()
	 * @covers \DaftFramework\DaftRouter\Source::RouterSources()
	 *
	 * @param list<class-string<Source>> $sources
	 * @param class-string<Router\NotFound>|class-string<Router\MethodNotAllowed>|class-string<Router\Found> $expected_response_class
	 */
	public function test_generate_route_collector_handler(
		array $sources,
		string $method,
		string $path,
		string $expected_response_class
	) : void {
		$handler = Router\Compiler::GenerateRouteCollectorHandler(
			...$sources
		);

		$dispatcher = simpleDispatcher($handler);

		$request = (new Fixtures\ServerRequest($path))->withMethod($method);

		$response = Router\Dispatch::Resolve($dispatcher, $request);

		static::assertInstanceOf($expected_response_class, $response);
	}

	/**
	 * @covers \DaftFramework\DaftRouter\Router\Dispatch::Resolve()
	 */
	public function test_dispatch_resolve_failure() : void
	{
		$dispatcher = simpleDispatcher(
			static function (RouteCollector $collector) : void {
				$collector->addRoute('GET', '/', []);
			}
		);

		$request = (new Fixtures\ServerRequest('/'))->withMethod('GET');

		static::expectException(UnexpectedValueException::class);
		static::expectExceptionMessage(
			'Dispatcher did not resolve to expected format!'
		);

		Router\Dispatch::Resolve($dispatcher, $request);
	}
}
