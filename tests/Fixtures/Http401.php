<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use BadMethodCallException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class Http401 implements ResponseInterface
{
	public const HTTP_UNAUTHORIZED = 401;

	/** @readonly */
	private string $protocol_version;

	public function __construct(string $version = '1.1')
	{
		$this->protocol_version = $version;
	}

	public function getStatusCode()
	{
		return self::HTTP_UNAUTHORIZED;
	}

	public function getReasonPhrase()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getProtocolVersion()
	{
		return $this->protocol_version;
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

	public function withStatus($code, $reasonPhrase = '')
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withProtocolVersion($version)
	{
		return new static($version);
	}

	public function withHeader($name, $value = '')
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withAddedHeader($name, $value = '')
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
