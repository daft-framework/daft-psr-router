<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class MutableRequest implements ServerRequestInterface
{
	private ServerRequestInterface $base_request;

	public function __construct(ServerRequestInterface $base_request)
	{
		$this->base_request = $base_request;
	}

	public function ObtainBaseRequest() : ServerRequestInterface
	{
		return $this->base_request;
	}

	public function getServerParams()
	{
		return $this->base_request->getServerParams();
	}

	public function getCookieParams()
	{
		return $this->base_request->getCookieParams();
	}

	public function withCookieParams(array $cookies)
	{
		$this->base_request = $this->base_request->withCookieParams($cookies);

		return $this;
	}

	public function getQueryParams()
	{
		return $this->base_request->getQueryParams();
	}

	public function withQueryParams(array $query)
	{
		$this->base_request = $this->base_request->withQueryParams($query);

		return $this;
	}

	public function getUploadedFiles()
	{
		return $this->base_request->getUploadedFiles();
	}

	public function withUploadedFiles(array $uploaded_files)
	{
		$this->base_request = $this->base_request->withUploadedFiles($uploaded_files);

		return $this;
	}

	public function getParsedBody()
	{
		return $this->base_request->getParsedBody();
	}

	public function withParsedBody($data)
	{
		$this->base_request = $this->base_request->withParsedBody($data);

		return $this;
	}

	public function getAttributes()
	{
		return $this->base_request->getAttributes();
	}

	public function getAttribute($name, $default = null)
	{
		return $this->base_request->getAttribute($name, $default);
	}

	public function withAttribute($name, $value)
	{
		$this->base_request = $this->base_request->withAttribute(
			$name,
			$value
		);

		return $this;
	}

	public function withoutAttribute($name)
	{
		$this->base_request = $this->base_request->withoutAttribute($name);

		return $this;
	}

	public function getRequestTarget()
	{
		return $this->base_request->getRequestTarget();
	}

	public function withRequestTarget($request_target)
	{
		$this->base_request = $this->base_request->withRequestTarget($request_target);

		return $this;
	}

	public function getMethod()
	{
		return $this->base_request->getMethod();
	}

	public function withMethod($method)
	{
		$this->base_request = $this->base_request->withMethod($method);

		return $this;
	}

	public function getUri()
	{
		return $this->base_request->getUri();
	}

	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		$this->base_request = $this->base_request->withUri(
			$uri,
			$preserveHost
		);

		return $this;
	}

	public function getProtocolVersion()
	{
		return $this->base_request->getProtocolVersion();
	}

	public function withProtocolVersion($version)
	{
		$this->base_request = $this->base_request->withProtocolVersion(
			$version
		);

		return $this;
	}

	public function getHeaders()
	{
		return $this->base_request->getHeaders();
	}

	public function hasHeader($name)
	{
		return $this->base_request->hasHeader($name);
	}

	public function getHeader($name)
	{
		return $this->base_request->getHeader($name);
	}

	public function getHeaderLine($name)
	{
		return $this->base_request->getHeaderLine($name);
	}

	public function withHeader($name, $value)
	{
		$this->base_request = $this->base_request->withHeader(
			$name,
			$value
		);

		return $this;
	}

	public function withAddedHeader($name, $value)
	{
		$this->base_request = $this->base_request->withAddedHeader(
			$name,
			$value
		);

		return $this;
	}

	public function withoutHeader($name)
	{
		$this->base_request = $this->base_request->withoutHeader($name);

		return $this;
	}

	public function getBody()
	{
		return $this->base_request->getBody();
	}

	public function withBody(StreamInterface $body)
	{
		$this->base_request = $this->base_request->withBody($body);

		return $this;
	}
}
