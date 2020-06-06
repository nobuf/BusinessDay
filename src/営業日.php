<?php /** @noinspection NonAsciiCharacters */

namespace App;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use RuntimeException;

class 営業日/* Business Day */
{
	/**
	 * @var DateTimeInterface
	 */
	private DateTimeInterface $dateTime;

	/**
	 * @var 祝日
	 */
	private 祝日 $祝日;

	public function __construct(DateTimeInterface $dateTime, 祝日 $祝日)
	{
		$this->dateTime = $dateTime;
		$this->祝日 = $祝日;
	}

	public function is営業日(): bool
	{
		return !$this->is土日()
			&& !$this->祝日->is祝日($this->dateTime);
	}

	private function is土日(): bool
	{
		return in_array($this->dateTime->format('l'), [
			'Saturday',
			'Sunday',
		], true);
	}

	/**
	 * 次の日以降の営業日を返す
	 *
	 * @return DateTimeImmutable
	 */
	public function next(): DateTimeImmutable
	{
		$nextDateTime = DateTime::createFromFormat('Y-m-d', $this->dateTime->format('Y-m-d'));

		while ($nextDateTime->add(new DateInterval('P1D'))) {
			$営業日 = new self($nextDateTime, $this->祝日);
			if ($営業日->is営業日()) {
				return DateTimeImmutable::createFromMutable($nextDateTime);
			}
		}

		throw new RuntimeException('Something went wrong');
	}
}