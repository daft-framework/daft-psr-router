<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
	private string $protocol_version = '1.0';

	public function getProtocolVersion()
	{
		return $this->protocol_version;
	}

	public function withProtocolVersion($version)
	{
		$this->protocol_version = $version;

		return $this;
	}

	public function getHeaders()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function hasHeader($name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getHeader($name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getHeaderLine($name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withHeader($name, $value)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withAddedHeader($name, $value)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withoutHeader($name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getBody()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withBody(StreamInterface $body)
	{
		throw new BadMethodCallException('Not Implemented!');
	}
}
