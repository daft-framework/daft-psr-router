<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use DaftFramework\DaftRouter\Interceptor;
use DaftFramework\DaftRouter\Modifier;
use DaftFramework\DaftRouter\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @psalm-import-type DISPATCH from Dispatch
 */
final class Found extends Dispatch
{
	/**
	 * @readonly
	 */
	public ServerRequestInterface $request;

	/**
	 * @var list<class-string<Interceptor>>
	 *
	 * @readonly
	 */
	public array $interceptors;

	/**
	 * @var list<class-string<Modifier>>
	 *
	 * @readonly
	 */
	public array $modifiers;

	/**
	 * @var class-string<Route>
	 *
	 * @readonly
	 */
	public string $route;

	/**
	 * @var array<string, string|null>
	 *
	 * @readonly
	 */
	public array $args;

	/**
	 * @param DISPATCH $dispatch
	 */
	public function __construct(
		ServerRequestInterface $request,
		array $dispatch
	) {
		$this->request = $request;
		$this->interceptors = $dispatch[1][Interceptor::class];
		$this->modifiers = $dispatch[1][Modifier::class];
		$this->route = $dispatch[1][0];
		$this->args = $dispatch[2];
	}

	public function handle() : ResponseInterface
	{
		$response = new RequestNotIntercepted();

		$here_is_one_we_made_earlier = new HereIsOneWeMadeEarlier($response);

		foreach ($this->interceptors as $interceptor) {
			$response = (new $interceptor())->GenerateProcessor()->process(
				$this->request,
				$here_is_one_we_made_earlier
			);

			if ( ! ($response instanceof RequestNotIntercepted)) {
				break;
			}
		}

		$route = $this->route;

		if ($response instanceof RequestNotIntercepted) {
			$response = (new $route($this->args))->GenerateHandler()->handle(
				$this->request
			);
		}

		foreach ($this->modifiers as $modifier) {
			$here_is_one_we_made_earlier = new HereIsOneWeMadeEarlier(
				$response
			);

			$response = (new $modifier())->GenerateProcessor()->process(
				$this->request,
				$here_is_one_we_made_earlier
			);
		}

		return $response;
	}
}
