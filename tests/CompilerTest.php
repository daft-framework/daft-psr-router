<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter;

use PHPUnit\Framework\TestCase as Base;

class CompilerTest extends Base
{
	/**
	 * @return list<array{
	 *	0:list<class-string<Source>>,
	 *	1:array<
	 *		string,
	 *		array<
	 *			string,
	 *			array{
	 *				DaftFramework\DaftRouter\Interceptor:list<
	 *					class-string<Interceptor>
	 *				>,
	 *				DaftFramework\DaftRouter\Modifier:list<
	 *					class-string<Modifier>
	 *				>,
	 *				0:class-string<Route>
	 *			}
	 *		>
	 *	>
	 * }>
	 */
	public function CompileDispatcherArrayProvider() : array
	{
		/**
		 * @var list<array{
		 *	0:list<class-string<Source>>,
		 *	1:array<
		 *		string,
		 *		array<
		 *			string,
		 *			array{
		 *				DaftFramework\DaftRouter\Interceptor:list<
		 *					class-string<Interceptor>
		 *				>,
		 *				DaftFramework\DaftRouter\Modifier:list<
		 *					class-string<Modifier>
		 *				>,
		 *				0:class-string<Route>
		 *			}
		 *		>
		 *	>
		 * }>
		 */
		return [
			[[Fixtures\EmptySource::class], []],
			[
				[Fixtures\BasicSource::class],
				[
					'GET' => [
						'/' => [
							Interceptor::class => [],
							Modifier::class => [
								Fixtures\ChangeProtocolVersion::class,
							],
							0 => Fixtures\Home::class,
						],
						'/secret' => [
							Interceptor::class => [
								Fixtures\DenyAccess::class,
							],
							Modifier::class => [
								Fixtures\ChangeProtocolVersion::class,
							],
							0 => Fixtures\Secret::class,
						],
						'/secret/not-secret' => [
							Interceptor::class => [
							],
							Modifier::class => [
								Fixtures\ChangeProtocolVersion::class,
							],
							0 => Fixtures\Secret::class,
						],
						'/secret/still-secret' => [
							Interceptor::class => [
								Fixtures\DenyAccess::class,
							],
							Modifier::class => [
								Fixtures\ChangeProtocolVersion::class,
							],
							0 => Fixtures\Secret::class,
						],
						'/secret/{number:\d+}' => [
							Interceptor::class => [
								Fixtures\DenyAccess::class,
							],
							Modifier::class => [
								Fixtures\ChangeProtocolVersion::class,
							],
							0 => Fixtures\Secret::class,
						],
						'/profile/{id:\d+}[~{slug:[^\/]+}]' => [
							Interceptor::class => [],
							Modifier::class => [
								Fixtures\ChangeProtocolVersion::class,
							],
							0 => Fixtures\Profile::class,
						],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider CompileDispatcherArrayProvider
	 *
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::ClosureForFilterThatMatchesUri()
	 * @covers \DaftFramework\DaftRouter\Router\Compiler::CompileDispatcherArray()
	 *
	 * @param list<class-string<Source>> $sources
	 * @param array<
	 *	string,
	 *	array<
	 *		string,
	 *		array{
	 *			DaftFramework\DaftRouter\Interceptor:list<
	 *				class-string<Interceptor>
	 *			>,
	 *			DaftFramework\DaftRouter\Modifier:list<
	 *				class-string<Modifier>
	 *			>,
	 *			0:class-string<Route>
	 *		}
	 *	>
	 * > $expected
	 */
	public function test_compile_dispatcher_array(
		array $sources,
		array $expected
	) : void {
		static::assertSame($expected, Router\Compiler::CompileDispatcherArray(
			...$sources
		));
	}
}
