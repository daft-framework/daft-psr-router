<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use DaftFramework\DaftRouter\Interceptor;
use DaftFramework\DaftRouter\Modifier;
use DaftFramework\DaftRouter\Route;
use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
	 * @param array{
	 *	0:Dispatcher::FOUND,
	 *	1:array{
	 *		DaftFramework\DaftRouter\Interceptor:list<class-string<Interceptor>>,
	 *		DaftFramework\DaftRouter\Modifier:list<class-string<Modifier>>,
	 *		0:class-string<Route>
	 *	},
	 *	2:array<string, string|null>
	 * } $dispatch
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

		$request = new MutableRequest($this->request);

		foreach ($this->interceptors as $interceptor) {
			$response = (new $interceptor())->GenerateProcessor()->process(
				$request,
				$here_is_one_we_made_earlier
			);

			if ( ! ($response instanceof RequestNotIntercepted)) {
				break;
			}
		}

		$route = $this->route;

		$request = $request->ObtainBaseRequest();

		if ($response instanceof RequestNotIntercepted) {
			$response = (new $route($this->args))->GenerateHandler()->handle(
				$request
			);
		}

		foreach ($this->modifiers as $modifier) {
			$here_is_one_we_made_earlier = new HereIsOneWeMadeEarlier(
				$response
			);

			$response = (new $modifier())->GenerateProcessor()->process(
				$request,
				$here_is_one_we_made_earlier
			);
		}

		return $response;
	}
}
