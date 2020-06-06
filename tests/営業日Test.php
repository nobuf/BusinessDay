<?php /** @noinspection NonAsciiCharacters */

use App\営業日;
use App\祝日;

const 祝日CSV = __DIR__ . '/../data/syukujitsu.csv';

test('営業日を new する', function () {
	assertInstanceOf(営業日::class, new 営業日(new DateTimeImmutable(), new 祝日(祝日CSV)));
});

test('与えられた日は営業日', function (bool $expected, string $input) {
	$営業日 = new 営業日(new DateTimeImmutable($input), new 祝日(祝日CSV));
	assertSame($expected, $営業日->is営業日());
})->with(function () {
	yield [false, '2020-06-06'];
	yield [false, '2020-06-07'];
	yield [true, '2020-06-08'];
	yield [false, '2020-07-23'];
	yield [false, '2020-07-24'];
	yield [false, '2020-07-25'];
	yield [true, '2020-07-27'];
	yield [false, '2021-01-01'];
	yield [false, '2021-01-01'];
	yield [true, '2021-01-04'];
});

test('次の営業日', function (string $expected, string $input) {
	$営業日 = new 営業日(new DateTimeImmutable($input), new 祝日(祝日CSV));
	assertSame($expected, $営業日->next()->format('Y-m-d'));
})->with(function () {
	yield ['2020-06-08', '2020-06-06'];
	yield ['2020-06-08', '2020-06-07'];
	yield ['2020-06-09', '2020-06-08'];
	yield ['2020-07-27', '2020-07-23'];
	yield ['2020-12-31', '2020-12-30'];
	yield ['2021-01-04', '2020-12-31'];
});