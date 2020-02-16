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
 * @template T1 as array{id:string, slug?:string}
 *
 * @template-implements TypedRoute<T1, 'GET', Slugged|Unslugged>
 */
class Profile implements TypedRoute
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

	public function TypedArgsFromUntyped(string $_method = null) : TypedArgs
	{
		if (isset($this->args['id'], $this->args['slug'])) {
			/** @var array{id:string, slug:string} */
			$args = $this->args;

			return new Slugged($args);
		}

		return new Unslugged(['id' => $this->args['id']]);
	}

	public function GenerateHandler() : RequestHandlerInterface
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public static function Routes() : array
	{
		return [
			'/profile/{id:\d+}[~{slug:[^\/]+}]' => ['GET'],
		];
	}

	/**
	 * @param Slugged|Unslugged $args
	 */
	public static function RouteStringFromTypedArgs(
		TypedArgs $args,
		string $method = null
	) : string {
		$out = '/profile/' . rawurlencode((string) $args->id);

		if ($args instanceof Slugged) {
			$out .= '~' . rawurlencode((string) $args->slug);
		}

		return $out;
	}
}
