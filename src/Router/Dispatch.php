<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use function array_filter;
use const ARRAY_FILTER_USE_BOTH;
use function array_values;
use function count;
use DaftFramework\DaftRouter\Interceptor;
use DaftFramework\DaftRouter\Modifier;
use DaftFramework\DaftRouter\Route;
use FastRoute\Dispatcher;
use function is_a;
use function is_array;
use function is_int;
use function is_null;
use function is_string;
use function preg_quote;
use function preg_replace;
use function preg_replace_callback;
use Psr\Http\Message\ServerRequestInterface;
use function rawurldecode;
use UnexpectedValueException;

/**
 * @psalm-type COMPILED_SUB_ARRAY = array{
 *	0:class-string<Route>,
 *	DaftFramework\DaftRouter\Interceptor:list<class-string<Interceptor>>,
 *	DaftFramework\DaftRouter\Modifier:list<class-string<Modifier>>
 * }
 * @psalm-type DISPATCH = array{
 *	0:Dispatcher::FOUND,
 *	1:COMPILED_SUB_ARRAY,
 *	2:array<string, string|null>
 * }
 */
abstract class Dispatch
{
	public const INDEX_STATUS = 0;

	public const INDEX_RESULT = 1;

	public const INDEX_ARGS = 2;

	public const INDEX_ROUTE = 0;

	public const EXPECTED_FOUND_SIZE = 3;

	public const EXPECTED_RESULT_SIZE = 3;

	/**
	 * @return NotFound|MethodNotAllowed|Found
	 */
	final public static function Resolve(
		Dispatcher $dispatcher,
		ServerRequestInterface $request,
		string $prefix = ''
	) : self {
		$regex = '/^' . preg_quote($prefix, '/') . '/';

		$path = preg_replace($regex, '/', $request->getUri()->getPath());

		$path = preg_replace_callback(
			'/\/([^\/]+)/',
			/**
			 * @param array<int, string> $matches
			 */
			static function (array $matches) : string {
				return '/' . rawurldecode($matches[1]);
			},
			preg_replace('/\/$/', '', preg_replace('/\/+/', '/', $path))
		);

		$path = '' === $path ? '/' : $path;

		$route_info = $dispatcher->dispatch($request->getMethod(), $path);

		if (Dispatcher::NOT_FOUND === $route_info[self::INDEX_STATUS]) {
			return new NotFound();
		} elseif (
			Dispatcher::METHOD_NOT_ALLOWED === $route_info[self::INDEX_STATUS]
		) {
			/** @var list<string> */
			$allowed_methods = $route_info[self::INDEX_RESULT];

			return new MethodNotAllowed($allowed_methods);
		} elseif (
			self::route_info_is_invalid($route_info)
		) {
			throw new UnexpectedValueException(
				'Dispatcher did not resolve to expected format!'
			);
		}

		return new Found($request, $route_info);
	}

	/**
	 * @psalm-assert-if-false DISPATCH $route_info
	 */
	private static function route_info_is_invalid(array $route_info) : bool
	{
		return
			Dispatcher::FOUND !== $route_info[self::INDEX_STATUS] ||
			self::EXPECTED_FOUND_SIZE !== count($route_info) ||
			! isset($route_info[self::INDEX_RESULT], $route_info[self::INDEX_ARGS]) ||
			! is_array($route_info[self::INDEX_RESULT]) ||
			! is_array($route_info[self::INDEX_ARGS]) ||
			self::EXPECTED_RESULT_SIZE !== count($route_info[self::INDEX_RESULT]) ||
			! isset(
				$route_info[self::INDEX_RESULT][Interceptor::class],
				$route_info[self::INDEX_RESULT][Modifier::class],
				$route_info[self::INDEX_STATUS]
			) ||
			! is_array($route_info[self::INDEX_RESULT][Interceptor::class]) ||
			! is_array($route_info[self::INDEX_RESULT][Modifier::class]) ||
			! is_string($route_info[self::INDEX_RESULT][self::INDEX_ROUTE]) ||
			! is_a($route_info[self::INDEX_RESULT][self::INDEX_ROUTE], Route::class, true) ||
			$route_info[self::INDEX_RESULT][Interceptor::class] !== array_values(array_filter(
				(array) $route_info[self::INDEX_RESULT][Interceptor::class],
				/**
				 * @param mixed $value
				 * @param array-key $key
				 */
				static function ($value, $key) : bool {
					return
						is_int($key) &&
						is_string($value) &&
						is_a($value, Interceptor::class, true);
				},
				ARRAY_FILTER_USE_BOTH
			)) ||
			$route_info[self::INDEX_RESULT][Modifier::class] !== array_values(array_filter(
				(array) $route_info[self::INDEX_RESULT][Modifier::class],
				/**
				 * @param mixed $value
				 * @param array-key $key
				 */
				static function ($value, $key) : bool {
					return
						is_int($key) &&
						is_string($value) &&
						is_a($value, Modifier::class, true);
				},
				ARRAY_FILTER_USE_BOTH
			)) ||
			$route_info[self::INDEX_ARGS] !== array_filter(
				$route_info[self::INDEX_ARGS],
				/**
				 * @param mixed $value
				 * @param array-key $key
				 */
				static function ($value, $key) : bool {
					return
						is_string($key) &&
						(is_string($value) || is_null($value));
				},
				ARRAY_FILTER_USE_BOTH
			);
	}
}
