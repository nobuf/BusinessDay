<?php /** @noinspection NonAsciiCharacters */

namespace App\非営業日;

use App\非営業日;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use SplFileObject;

class 祝日 implements 非営業日
{
    private string $filePath;

    /** @var DateTimeImmutable[] */
    private array $祝日s = [];

    /**
     * 祝日 constructor.
     * @param string $filePath
     * @throws Exception
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->fetchAll();
    }

    private function fetchAll(): void
    {
        $file = new SplFileObject($this->filePath);

        foreach ($file as $line) {
            $columns = explode(',', $line);
            $祝日 = DateTimeImmutable::createFromFormat('Y/n/j', $columns[0]);
            if ($祝日) {
                $this->祝日s[] = $祝日;
            }
        }
    }

    public function is非営業日(DateTimeInterface $dateTime): bool
    {
        $dateTimeAsString = $dateTime->format('Y-m-d');

        foreach ($this->祝日s as $祝日) {
            if ($祝日->format('Y-m-d') === $dateTimeAsString) {
                return true;
            }
        }

        return false;
    }
}
