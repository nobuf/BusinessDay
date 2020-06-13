<?php /** @noinspection NonAsciiCharacters */

use App\営業日Factory;
use App\非営業日\その他の休日;
use App\非営業日\土日;
use App\非営業日\祝日;
use App\非営業日Exception;
use PHPUnit\Framework\TestCase;

class 営業日FactoryTest extends TestCase
{
    const 祝日CSV = __DIR__ . '/../data/syukujitsu.csv';

    /**
     * @dataProvider createDataProvider
     * @param string $expected
     * @param string $input
     * @throws Exception
     */
    public function testCreate(
        string $expected,
        string $input
    )
    {
        $factory = new 営業日Factory([new 土日()]);

        $this->assertSame(
            $expected,
            $factory->create(new DateTimeImmutable($input))->format('Y-m-d')
        );
    }

    public function createDataProvider()
    {
        yield ['2020-06-12', '2020-06-12'];
        yield ['2020-06-15', '2020-06-15'];
    }

    /**
     * @dataProvider create土日祝日DataProvider
     * @param string $input
     * @throws Exception
     */
    public function testCreateWith土日祝日(string $input)
    {
        $this->expectException(非営業日Exception::class);

        $factory = new 営業日Factory([
            new 土日(),
            new 祝日(self::祝日CSV),
        ]);

        $factory->create(new DateTimeImmutable($input));
    }

    public function create土日祝日DataProvider()
    {
        yield ['2020-05-06'];
        yield ['2020-06-13'];
        yield ['2020-06-14'];
        yield ['2021-01-01'];
    }

    /**
     * @dataProvider nextDataProvider
     * @param string $expected
     * @param string $input
     * @throws Exception
     */
    public function testNext(string $expected, string $input)
    {
        $factory = new 営業日Factory([
            new 土日(),
            new 祝日(self::祝日CSV),
            new その他の休日(),
        ]);

        $this->assertSame(
            $expected,
            $factory->next(new DateTimeImmutable($input))->format('Y-m-d')
        );
    }

    public function nextDataProvider()
    {
        yield ['2020-06-08', '2020-06-06'];
        yield ['2020-06-08', '2020-06-07'];
        yield ['2020-06-09', '2020-06-08'];
        yield ['2020-07-27', '2020-07-23'];
        yield ['2020-12-29', '2020-12-28'];
        yield ['2021-01-04', '2020-12-29'];
        yield ['2021-01-04', '2020-12-30'];
        yield ['2021-01-04', '2020-12-31'];
    }

    /**
     * @dataProvider create年末年始DataProvider
     * @param string $input
     * @throws 非営業日Exception
     * @throws Exception
     */
    public function testCreateWith年末年始(string $input)
    {
        $this->expectException(非営業日Exception::class);

        $factory = new 営業日Factory([
            new 土日(),
            new 祝日(self::祝日CSV),
            new その他の休日(),
        ]);

        $factory->create(new DateTimeImmutable($input));
    }

    public function create年末年始DataProvider()
    {
        yield ['2020-12-30'];
        yield ['2020-12-31'];
        yield ['2021-01-01'];
        yield ['2021-01-02'];
        yield ['2021-01-03'];
    }
}
