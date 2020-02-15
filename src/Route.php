<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * @psalm-type THTTP = 'GET'|'POST'|'CONNECT'|'DELETE'|'HEAD'|'OPTIONS'|'PATCH'|'PURGE'|'PUT'|'TRACE'
 *
 * @template T1 as array<string, scalar|null>
 */
interface Route
{
	/**
	 * @param T1 $args
	 */
	public function __construct(array $args);

	public function GenerateHandler() : RequestHandlerInterface;

	/**
	 * @return array<string, list<THTTP>> an array of URIs & methods
	 */
	public static function Routes() : array;
}
