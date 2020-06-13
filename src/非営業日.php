<?php /** @noinspection NonAsciiCharacters */

namespace App;

use DateTimeInterface;

interface 非営業日
{
	public function is非営業日(DateTimeInterface $dateTime): bool;
}
