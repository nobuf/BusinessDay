<?php /** @noinspection NonAsciiCharacters */

use App\非営業日\祝日;

test('与えられた日は祝日', function (bool $expected, string $input) {
    $祝日 = new 祝日(
        __DIR__ . '/../data/syukujitsu.csv'
    );

    assertSame($expected, $祝日->is非営業日(new DateTimeImmutable($input)));
})->with(function () {
    yield [false, '2020-06-05'];
    yield [false, '2020-06-06'];
    yield [false, '2020-06-07'];
    yield [false, '2020-06-08'];
    yield [true, '2020-01-01'];
    yield [true, '2020-07-23'];
    yield [true, '2020-07-24'];
    yield [false, '2020-12-31'];
    yield [true, '2021-10-11'];
    yield [false, '2021-10-12'];

    // date with time
    yield [true, '2020-07-23 01:23'];
});
