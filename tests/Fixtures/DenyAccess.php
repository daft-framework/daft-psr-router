<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\Interceptor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DenyAccess implements Interceptor
{
	public function __construct()
	{
	}

	public function GenerateProcessor() : MiddlewareInterface
	{
		return new class() implements MiddlewareInterface {
			public function process(
				ServerRequestInterface $request,
				RequestHandlerInterface $handler
			) : ResponseInterface {
				if (
					'1' === (
						$request->getQueryParams()['allow-through'] ?? null
					)
				) {
					return $handler->handle($request);
				}

				return new Http401();
			}
		};
	}

	public static function RouteMustNotStartWith() : array
	{
		return ['/secret/not-secret'];
	}

	public static function RouteMustStartWith() : array
	{
		return ['/secret'];
	}
}
