<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
	private array $query_params = [];

	public function getServerParams()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getCookieParams()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withCookieParams(array $cookies)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getQueryParams()
	{
		return $this->query_params;
	}

	public function withQueryParams(array $query)
	{
		$this->query_params = $query;

		return $this;
	}

	public function getUploadedFiles()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withUploadedFiles(array $files)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getParsedBody()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withParsedBody($data)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getAttributes()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getAttribute($name, $default = null)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withAttribute($name, $value)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withoutAttribute($name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}
}
