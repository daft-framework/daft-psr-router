<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use DaftFramework\DaftRouter\Interceptor;
use DaftFramework\DaftRouter\Modifier;
use DaftFramework\DaftRouter\Route;
use FastRoute\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;

abstract class Dispatch
{
	const INDEX_STATUS = 0;

	const INDEX_RESULT = 1;

	const INDEX_ARGS = 2;

	const INDEX_ROUTE = 0;

	const EXPECTED_FOUND_SIZE = 3;

	const EXPECTED_RESULT_SIZE = 3;

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
			$route_info[self::INDEX_RESULT][Interceptor::class] !== array_filter(
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
			) ||
			$route_info[self::INDEX_RESULT][Modifier::class] !== array_filter(
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
			) ||
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
			)
		) {
			throw new UnexpectedValueException(
				'Dispatcher did not resolve to expected format!'
			);
		}

		/**
		 * @var array{
		 *	0:Dispatcher::FOUND,
		 *	1:array{
		 *		DaftFramework\DaftRouter\Interceptor:list<class-string<Interceptor>>,
		 *		DaftFramework\DaftRouter\Modifier:list<class-string<Modifier>>,
		 *		0:class-string<Route>
		 *	},
		 *	2:array<string, string|null>
		 * }
		 */
		$route_info = $route_info;

		return new Found($request, $route_info);
	}
}
