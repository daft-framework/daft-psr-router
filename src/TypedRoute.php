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
	public function __construct();

	/**
	 * @psalm-param T1 $args
	 * @psalm-param T2 $method
	 *
	 * @psalm-return T3
	 */
	public function TypedArgsFromUntyped(
		array $args,
		string $method
	) : TypedArgs;

	/**
	 * @psalm-param T3 $args
	 */
	public function RouteStringFromTypedArgs(
		TypedArgs $args,
		string $method
	) : string;
}
