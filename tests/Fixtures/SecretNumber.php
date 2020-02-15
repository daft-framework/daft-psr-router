<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\TypedArgs;
use InvalidArgumentException;

/**
 * @template T1 as array{number:string}
 *
 * @template-implements TypedArgs<T1>
 */
class SecretNumber implements TypedArgs
{
	/** @readonly */
	public int $number;

	public function __construct(array $args)
	{
		if ( ! ctype_digit($args['number'])) {
			throw new InvalidArgumentException('number was not intable!');
		}

		$this->number = (int) $args['number'];
	}
}
