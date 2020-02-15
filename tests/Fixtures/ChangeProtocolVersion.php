<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\Modifier;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChangeProtocolVersion implements Modifier
{
	public function __construct()
	{
	}

	public function GenerateProcessor() : MiddlewareInterface
	{
		/** @var MiddlewareInterface */
		return new class() implements MiddlewareInterface {
			public function process(
				ServerRequestInterface $request,
				RequestHandlerInterface $handler
			) : ResponseInterface {
				$response = $handler->handle($request);
				if ('1.0' === $request->getProtocolVersion()) {
					return $response->withProtocolVersion('1.1');
				} elseif ('1.1' === $request->getProtocolVersion()) {
					return $response->withProtocolVersion('2.0');
				}

				return $response->withProtocolVersion('1.0');
			}
		};
	}

	public static function RouteMustNotStartWith() : array
	{
		return [];
	}

	public static function RouteMustStartWith() : array
	{
		return ['/'];
	}
}
