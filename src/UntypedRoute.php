<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

/**
 * @psalm-type THTTP = 'GET'|'POST'|'CONNECT'|'DELETE'|'HEAD'|'OPTIONS'|'PATCH'|'PURGE'|'PUT'|'TRACE'
 *
 * @template T1 as array<string, scalar|null>
 *
 * @template-extends Route<T1>
 */
interface UntypedRoute extends Route
{
	/**
	 * @param THTTP $method
	 */
	public static function RouteStringFromMethod(
		string $method = null
	) : string;
}
