<?php /** @noinspection NonAsciiCharacters */

namespace App;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Exception;
use RuntimeException;

class 営業日Factory
{
	/**
	 * @var 非営業日[]
	 */
	private array $listOf非営業日;

	/**
	 * 営業日Factory constructor.
	 * @param 非営業日[] $listOf非営業日
	 */
	public function __construct(array $listOf非営業日 = [])
	{
		$this->listOf非営業日 = $listOf非営業日;
	}

	/**
	 * @param DateTimeInterface $dateTime
	 * @return 営業日
	 * @throws 非営業日Exception
	 * @throws Exception
	 */
	public function create(DateTimeInterface $dateTime): 営業日
	{
		if ($this->is非営業日($dateTime)) {
			throw new 非営業日Exception(sprintf(
				'%s is not 営業日',
				$dateTime->format('Y-m-d')
			));
		}

		return new 営業日($dateTime->format('Y-m-d'));
	}

	private function is非営業日(DateTimeInterface $dateTime): bool
	{
		foreach ($this->listOf非営業日 as $非営業日) {
			if ($非営業日->is非営業日($dateTime)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 次の日以降の営業日を返す
	 *
	 * @param DateTimeInterface $dateTime
	 * @return 営業日
	 * @throws Exception
	 */
	public function next(DateTimeInterface $dateTime): 営業日
	{
		$nextDateTime = DateTime::createFromFormat('Y-m-d', $dateTime->format('Y-m-d'));

		while ($nextDateTime->add(new DateInterval('P1D'))) {
			try {
				return $this->create($nextDateTime);
			} catch (非営業日Exception $e) {
				continue;
			}
		}

		throw new RuntimeException('Something went wrong');
	}
}
