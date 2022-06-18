<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier;
use DaftFramework\DaftRouter\Router\RequestNotIntercepted;
use DaftFramework\DaftRouter\TypedArgs;
use DaftFramework\DaftRouter\TypedRoute;
use DaftFramework\DaftRouter\UntypedRoute;
use Psr\Http\Server\RequestHandlerInterface;
use function rawurlencode;
use UnexpectedValueException;

/**
 * @template T1 as array{number?:string}
 *
 * @template-implements TypedRoute<T1, 'GET', SecretNumber>
 * @template-implements UntypedRoute<T1>
 */
class Secret implements TypedRoute, UntypedRoute
{
	public function __construct()
	{
	}

	/**
	 * @psalm-param T1 $args
	 *
	 * @return SecretNumber
	 */
	public function TypedArgsFromUntyped(array $args, string $method = null) : TypedArgs
	{
		/** @var string|null */
		$number = $args['number'] ?? null;

		if (null !== $number) {
			return new SecretNumber([
				'number' => $number,
			]);
		}

		throw new UnexpectedValueException(
			'Cannot call ' .
			__METHOD__ .
			'() when number is not present on ' .
			static::class .
			'::$args'
		);
	}

	public function GenerateHandler(array $args) : RequestHandlerInterface
	{
		return new HereIsOneWeMadeEarlier(new RequestNotIntercepted());
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
	 * @param SecretNumber $args
	 */
	public function RouteStringFromTypedArgs(
		TypedArgs $args,
		string $method = null
	) : string {
		return '/secret/' . rawurlencode((string) $args->number);
	}

	public static function RouteStringFromMethod(
		string $method = null
	) : string {
		return '/secret';
	}
}
