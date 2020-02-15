<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

use BadMethodCallException;
use PHPUnit\Framework\TestCase as Base;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class CoverageTest extends Base
{
	/**
	 * @return list<array{
	 *	0:class-string<MessageInterface>,
	 *	1:list<mixed>|array<empty, empty>,
	 *	2:string,
	 *	3:list<mixed>|array<empty, empty>
	 * }>
	 */
	public function failureProvider() : array
	{
		$dud_stream = new class() implements StreamInterface {
			public function __toString()
			{
				return '';
			}

			public function close()
			{
			}

			public function detach()
			{
			}

			public function getSize()
			{
				return 0;
			}

			public function tell()
			{
				return 0;
			}

			public function eof()
			{
				return true;
			}

			public function isSeekable()
			{
				return false;
			}

			public function seek($_offset, $_whence = SEEK_SET) : void
			{
				throw new RuntimeException('Not Implemented!');
			}

			public function rewind() : void
			{
			}

			public function isWritable()
			{
				return false;
			}

			public function write($string)
			{
				throw new RuntimeException('Not Implemented!');
			}

			public function isReadable()
			{
				return false;
			}

			public function read($length)
			{
				return '';
			}

			public function getContents()
			{
				return '';
			}

			public function getMetaData($key = null)
			{
			}
		};

		return [
			[Router\RequestNotIntercepted::class, [], 'getReasonPhrase', []],
			[Router\RequestNotIntercepted::class, [], 'getHeaders', []],
			[Router\RequestNotIntercepted::class, [], 'hasHeader', ['foo']],
			[Router\RequestNotIntercepted::class, [], 'getHeader', ['foo']],
			[Router\RequestNotIntercepted::class, [], 'getHeaderLine', ['foo']],
			[Router\RequestNotIntercepted::class, [], 'withStatus', [200]],
			[
				Router\RequestNotIntercepted::class,
				[],
				'withHeader',
				['foo', 'bar'],
			],
			[
				Router\RequestNotIntercepted::class,
				[],
				'withAddedHeader',
				['foo', 'bar'],
			],
			[Router\RequestNotIntercepted::class, [], 'withoutHeader', ['foo']],
			[Router\RequestNotIntercepted::class, [], 'getBody', []],
			[Router\RequestNotIntercepted::class, [], 'withBody', [
				$dud_stream,
			]],
		];
	}

	/**
	 * @dataProvider failureProvider
	 *
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::__construct()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getBody()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getHeader()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getHeaderLine()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getHeaders()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::getReasonPhrase()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::hasHeader()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withAddedHeader()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withBody()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withHeader()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withoutHeader()
	 * @covers \DaftFramework\DaftRouter\Router\RequestNotIntercepted::withStatus()
	 *
	 * @param class-string<MessageInterface> $type
	 * @param list<mixed>|array<empty, empty> $type_args
	 * @param list<mixed>|array<empty, empty> $args
	 */
	public function test_method_not_implemented(
		string $type,
		array $type_args,
		string $method,
		array $args
	) : void {
		$response = new $type(...$type_args);

		static::expectException(BadMethodCallException::class);
		static::expectExceptionMessage('Not Implemented!');

		$response->$method(...$args);
	}
}
