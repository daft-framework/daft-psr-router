<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
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
	public function __construct()
	{
	}

	public function TypedArgsFromUntyped(array $args, string $method = null) : TypedArgs
	{
		if (isset($args['id'], $args['slug'])) {
			/** @var array{id:string, slug:string} */
			$args = $args;

			return new Slugged($args);
		}

		return new Unslugged(['id' => $args['id']]);
	}

	public function GenerateHandler(array $args) : RequestHandlerInterface
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
	 * @psalm-param Slugged|Unslugged $args
	 */
	public function RouteStringFromTypedArgs(
		TypedArgs $args,
		string $method = null
	) : string {
		$out = '/profile/' . rawurlencode((string) $args->id);

		if ($args instanceof Slugged) {
			$out .= '~' . rawurlencode($args->slug);
		}

		return $out;
	}
}
