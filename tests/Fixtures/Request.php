<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
	private string $method = 'GET';

	private Uri $uri;

	public function __construct(string $path)
	{
		$this->uri = new Uri($path);
	}

	public function getRequestTarget()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withRequestTarget($_request_target)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function withMethod($method)
	{
		$this->method = $method;

		return $this;
	}

	public function getUri()
	{
		return $this->uri;
	}

	public function withUri(UriInterface $_uri, $_preserve_host = false)
	{
		throw new BadMethodCallException('Not Implemented!');
	}
}
