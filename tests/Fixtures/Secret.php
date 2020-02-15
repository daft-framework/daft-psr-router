<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
use DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier;
use DaftFramework\DaftRouter\Router\RequestNotIntercepted;
use DaftFramework\DaftRouter\TypedArgs;
use DaftFramework\DaftRouter\TypedRoute;
use Psr\Http\Server\RequestHandlerInterface;
use function rawurlencode;

/**
 * @template T1 as array{number?:string}
 *
 * @template-implements TypedRoute<T1, 'GET', SecretNumber|null>
 */
class Secret implements TypedRoute
{
	/**
	 * @var T1
	 *
	 * @readonly
	 */
	private array $args;

	/**
	 * @param T1 $args
	 */
	public function __construct(array $args)
	{
		$this->args = $args;
	}

	public function TypedArgsFromUntyped(string $_method = null) : ? TypedArgs
	{
		if (isset($this->args['number']) && is_string($this->args['number'])) {
			/** @var array{number:string} */
			$args = $this->args;

			return new SecretNumber($args);
		}

		return null;
	}

	public function GenerateHandler() : RequestHandlerInterface
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public static function Routes() : array
	{
		return [
			'/secret' => ['GET'],
			'/secret/not-secret' => ['GET'],
			'/secret/still-secret' => ['GET'],
			'/secret/{number:\d+}' => ['GET'],
		];
	}

	/**
	 * @param SecretNumber|null $args
	 */
	public static function RouteStringFromTypedArgs(
		? TypedArgs $args,
		string $method = null
	) : string {
		if (null === $args) {
			return '/secret';
		}

		return '/secret/' . rawurlencode((string) $args->number);
	}
}
