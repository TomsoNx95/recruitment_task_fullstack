<?php

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Traits\TraitDTO;
use DateTimeImmutable;

/**
 * Class ExchangeRateDTO
 */
final class ExchangeRateDTO
{
    use TraitDTO;

    /**
     * @var float
     */
    protected $midValue;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $fromFullname;

    /**
     * @var DateTimeImmutable
     */
    protected $date;

    /**
     * @return float
     */
    public function getMidValue(): float
    {
        return round($this->midValue, 2);
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getFromFullname(): string
    {
        return $this->fromFullname;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
