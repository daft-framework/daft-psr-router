<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use function array_filter;
use function array_values;
use Closure;
use DaftFramework\DaftRouter\Interceptor;
use DaftFramework\DaftRouter\Modifier;
use DaftFramework\DaftRouter\Route;
use DaftFramework\DaftRouter\Source;
use FastRoute\RouteCollector;
use function is_a;
use function mb_strpos;
use SignpostMarv\DaftInterfaceCollector\StaticMethodCollector;

/**
 * @psalm-import-type COMPILED_SUB_ARRAY from Dispatch
 *
 * @psalm-type COMPILED_ARRAY = array<
 *	string,
 *	array<
 *		string,
 *		COMPILED_SUB_ARRAY
 *	>
 * >
 */
final class Compiler
{
	public const COLLECTOR_CONFIG = [
		Source::class => [
			'RouterSources' => [
				Interceptor::class,
				Modifier::class,
				Route::class,
				Source::class,
			],
		],
	];

	public const COLLECTED_INTERFACES = [
		Interceptor::class,
		Modifier::class,
		Route::class,
	];

	/**
	 * @param class-string<Source> ...$sources
	 *
	 * @return Closure(RouteCollector):void
	 */
	public static function GenerateRouteCollectorHandler(
		string ...$sources
	) : Closure {
		return static function (
			RouteCollector $collector
		) use ($sources) : void {
			foreach (
				self::CompileDispatcherArray(...$sources) as $method => $uris
			) {
				foreach ($uris as $uri => $handlers) {
					$collector->addRoute($method, $uri, $handlers);
				}
			}
		};
	}

	/**
	 * @param class-string<Source> ...$sources
	 *
	 * @return COMPILED_ARRAY
	 */
	public static function CompileDispatcherArray(string ...$sources) : array
	{
		/**
		 * @var iterable<
		 *	class-string<Route>|
		 *	class-string<Interceptor>|
		 *	class-string<Modifier>
		 * >
		 */
		$things = (new StaticMethodCollector(
			self::COLLECTOR_CONFIG,
			self::COLLECTED_INTERFACES
		))->Collect(...$sources);

		/** @var list<class-string<Route>> */
		$routes = [];

		/** @var list<class-string<Interceptor>> */
		$interceptors = [];

		/** @var list<class-string<Modifier>> */
		$modifiers = [];

		foreach ($things as $thing) {
			if (is_a($thing, Route::class, true)) {
				$routes[] = $thing;
			} elseif (is_a($thing, Interceptor::class, true)) {
				$interceptors[] = $thing;
			} elseif (is_a($thing, Modifier::class, true)) {
				$modifiers[] = $thing;
			}
		}

		$out = [];

		foreach ($routes as $route) {
			foreach ($route::Routes() as $uri => $methods) {
				$filter = static::ClosureForFilterThatMatchesUri($uri);

				$append = [
					Interceptor::class => array_values(array_filter($interceptors, $filter)),
					Modifier::class => array_values(array_filter($modifiers, $filter)),
					0 => $route,
				];

				foreach ($methods as $method) {
					if ( ! isset($out[$method])) {
						$out[$method] = [];
					}

					$out[$method][$uri] = $append;
				}
			}
		}

		/**
		 * @var COMPILED_ARRAY
		 */
		return $out;
	}

	/**
	 * @return Closure(class-string<Interceptor>|class-string<Modifier>):bool
	 */
	private static function ClosureForFilterThatMatchesUri(
		string $uri
	) : Closure {
		return
			/**
			 * @param class-string<Interceptor>|class-string<Modifier> $middleware
			 */
			static function (string $middleware) use ($uri) : bool {
				foreach ($middleware::RouteMustNotStartWith() as $exception) {
					if (0 === mb_strpos($uri, $exception)) {
						return false;
					}
				}

				foreach ($middleware::RouteMustStartWith() as $requirement) {
					if (0 === mb_strpos($uri, $requirement)) {
						return true;
					}
				}

				return false;
			};
	}
}
