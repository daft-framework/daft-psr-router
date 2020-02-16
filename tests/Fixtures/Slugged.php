<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

/**
 * @template T1 as array{id:string, slug:string}
 *
 * @template-extends Unslugged<T1>
 */
class Slugged extends Unslugged
{
	/** @readonly */
	public string $slug;

	/**
	 * @param T1 $args
	 */
	public function __construct(array $args)
	{
		parent::__construct($args);

		$this->slug = $args['slug'];
	}
}
