<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\Route;
use DaftFramework\DaftRouter\Router\HereIsOneWeMadeEarlier;
use DaftFramework\DaftRouter\Router\RequestNotIntercepted;
use Psr\Http\Server\RequestHandlerInterface;

class Home implements Route
{
	public function __construct()
	{
	}

	public function GenerateHandler(array $args) : RequestHandlerInterface
	{
		return new HereIsOneWeMadeEarlier(new RequestNotIntercepted());
	}

	public static function Routes() : array
	{
		return [
			'/' => ['GET'],
		];
	}
}
