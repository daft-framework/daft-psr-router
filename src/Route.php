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
	public function __construct();

	/**
	 * @psalm-param T1 $args
	 */
	public function GenerateHandler(array $args) : RequestHandlerInterface;

	/**
	 * @return array<string, list<THTTP>> an array of URIs & methods
	 */
	public static function Routes() : array;
}
