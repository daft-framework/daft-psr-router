<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
	private string $path;

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function __toString()
	{
		return $this->path;
	}

	public function getScheme()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getAuthority()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getUserInfo()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getHost()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getPort()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getQuery()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getFragment()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withScheme($_scheme)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withHost($_host)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withPort($_port)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withPath($path)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withQuery($_query)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withFragment($_fragment)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withUserInfo($_user, $_password = null)
	{
		throw new BadMethodCallException('Not Implemented!');
	}
}
