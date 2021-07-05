<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HereIsOneWeMadeEarlier implements RequestHandlerInterface
{
	private ResponseInterface $made_earlier;

	public function __construct(ResponseInterface $made_earlier)
	{
		$this->made_earlier = $made_earlier;
	}

	public function handle(
		ServerRequestInterface $request
	) : ResponseInterface {
		return $this->made_earlier;
	}
}
