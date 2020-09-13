<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use BadMethodCallException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class RequestNotIntercepted implements ResponseInterface
{
	const HTTP_INTERNAL_SERVER_ERROR = 500;

	/** @readonly */
	private string $protocol_version;

	public function __construct(string $protocol_version = '1.1')
	{
		$this->protocol_version = $protocol_version;
	}

	public function getStatusCode()
	{
		return self::HTTP_INTERNAL_SERVER_ERROR;
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

	public function withProtocolVersion($protocol_version)
	{
		return new self($protocol_version);
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
