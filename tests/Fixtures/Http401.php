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
	const HTTP_UNAUTHORIZED = 401;

	/** @readonly */
	private string $protocol_version;

	public function __construct(string $protocol_version = '1.1')
	{
		$this->protocol_version = $protocol_version;
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

	public function hasHeader($_name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getHeader($_name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getHeaderLine($_name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withStatus($_code, $_reasonPhrase = '')
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withProtocolVersion($protocol_version)
	{
		return new static($protocol_version);
	}

	public function withHeader($_name, $_value = '')
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withAddedHeader($_name, $_value = '')
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withoutHeader($_name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getBody()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withBody(StreamInterface $_body)
	{
		throw new BadMethodCallException('Not Implemented!');
	}
}
