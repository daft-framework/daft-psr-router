<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use function ctype_digit;
use DaftFramework\DaftRouter\TypedArgs;
use InvalidArgumentException;

/**
 * @template T1 as array{id:string}
 *
 * @template-implements TypedArgs<T1>
 */
class Unslugged implements TypedArgs
{
	/** @readonly */
	public int $id;

	/**
	 * @param T1 $args
	 */
	public function __construct(array $args)
	{
		if ( ! ctype_digit($args['id'])) {
			throw new InvalidArgumentException('id was not intable!');
		}

		$this->id = (int) $args['id'];
	}
}
