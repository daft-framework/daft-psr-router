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
	public function getServerParams()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getCookieParams()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withCookieParams(array $_cookies)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getQueryParams()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withQueryParams(array $_query)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getUploadedFiles()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withUploadedFiles(array $_files)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getParsedBody()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withParsedBody($_data)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getAttributes()
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function getAttribute($_name, $_default = null)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withAttribute($_name, $_value)
	{
		throw new BadMethodCallException('Not Implemented!');
	}

	public function withoutAttribute($_name)
	{
		throw new BadMethodCallException('Not Implemented!');
	}
}
