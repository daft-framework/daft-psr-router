<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\DaftRouter\Fixtures;

use DaftFramework\DaftRouter\Source;

final class EmptySource implements Source
{
	public static function RouterSources() : array
	{
		return [];
	}
}
