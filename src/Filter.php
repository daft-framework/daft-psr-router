<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

use Psr\Http\Server\MiddlewareInterface;

interface Filter
{
	public function __construct();

	public function GenerateProcessor() : MiddlewareInterface;

	/**
	 * @return list<string> URI prefixes
	 */
	public static function RouteMustNotStartWith() : array;

	/**
	 * @return list<string> URI prefixes
	 */
	public static function RouteMustStartWith() : array;
}
