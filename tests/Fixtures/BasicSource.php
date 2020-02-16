<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\Source;

final class BasicSource implements Source
{
	public static function RouterSources() : array
	{
		return [
			EmptySource::class,
			DenyAccess::class,
			ChangeProtocolVersion::class,
			Home::class,
			Secret::class,
			Profile::class,
		];
	}
}
