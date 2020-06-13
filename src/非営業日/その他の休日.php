<?php /** @noinspection NonAsciiCharacters */

namespace App\非営業日;

use App\非営業日;
use DateTimeInterface;

/**
 * 会社固有の休日など
 *
 */
class その他の休日 implements 非営業日
{
	const 休日 = [
		'2020-12-30',
		'2020-12-31',
		'2021-01-01',
		'2021-01-02',
		'2021-01-03',
	];

	public function is非営業日(DateTimeInterface $dateTime): bool
	{
		return in_array($dateTime->format('Y-m-d'), self::休日, true);
	}
}
