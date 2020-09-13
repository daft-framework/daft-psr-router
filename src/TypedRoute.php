<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

/**
 * @template T1 as array<string, scalar|null>
 * @template T2 as 'GET'|'POST'|'CONNECT'|'DELETE'|'HEAD'|'OPTIONS'|'PATCH'|'PURGE'|'PUT'|'TRACE'
 * @template T3 as TypedArgs
 *
 * @template-extends Route<T1>
 */
interface TypedRoute extends Route
{
	/**
	 * @param T1 $args
	 */
	public function __construct(array $args);

	/**
	 * @param T2 $method
	 *
	 * @return T3
	 */
	public function TypedArgsFromUntyped(
		string $method
	) : TypedArgs;

	public static function RouteStringFromTypedArgs(
		TypedArgs $args,
		string $method
	) : string;
}
