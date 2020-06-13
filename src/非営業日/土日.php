<?php /** @noinspection NonAsciiCharacters */

namespace App\非営業日;

use App\非営業日;
use DateTimeInterface;

class 土日 implements 非営業日
{
    public function is非営業日(DateTimeInterface $dateTime): bool
    {
        return in_array($dateTime->format('l'), [
            'Saturday',
            'Sunday',
        ], true);
    }
}
