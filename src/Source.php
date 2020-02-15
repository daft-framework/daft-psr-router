<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

interface Source
{
	/**
	 * @return list<
	 *	class-string<Route>|
	 *	class-string<Interceptor>|
	 *	class-string<Modifier>|
	 *	class-string<Source>
	 * >
	 */
	public static function RouterSources() : array;
}
