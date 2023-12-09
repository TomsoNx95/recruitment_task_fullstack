<?php

declare(strict_types=1);

namespace App\ValueObject;

use DateTimeImmutable;

/**
 * ExchangeRate
 */
class ExchangeRate
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var float
     */
    private $mid;

    /**
     * @var float
     */
    private $buy;

    /**
     * @var float
     */
    private $sell;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return ExchangeRate
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return ExchangeRate
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return float
     */
    public function getMid(): float
    {
        return $this->mid;
    }

    /**
     * @param float $mid
     * @return ExchangeRate
     */
    public function setMid(float $mid): self
    {
        $this->mid = $mid;

        return $this;
    }

    /**
     * @return float
     */
    public function getBuy(): float
    {
        return $this->buy;
    }

    /**
     * @param float $buy
     * @return ExchangeRate
     */
    public function setBuy(float $buy): self
    {
        $this->buy = $buy;

        return $this;
    }

    /**
     * @return float
     */
    public function getSell(): float
    {
        return $this->sell;
    }

    /**
     * @param float $sell
     * @return ExchangeRate
     */
    public function setSell(float $sell): self
    {
        $this->sell = $sell;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return ExchangeRate
     */
    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
