<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

final class MethodNotAllowed extends Dispatch
{
	/**
	 * @var list<string>
	 *
	 * @readonly
	 */
	public array $allowed_methods;

	/**
	 * @param list<string> $allowed_methods
	 */
	public function __construct(array $allowed_methods)
	{
		$this->allowed_methods = $allowed_methods;
	}
}
