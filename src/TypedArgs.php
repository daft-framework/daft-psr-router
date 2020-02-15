<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

/**
 * @template T1 as array<string, scalar|null>
 */
interface TypedArgs
{
	/**
	 * @param T1 $args
	 */
	public function __construct(array $args);
}
