<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ObjectToArrayTrait;
use DateTimeImmutable;

/**
 * ExchangeRate
 */
class ExchangeRate
{
    use ObjectToArrayTrait;

    /**
     * @var string
     */
    private $from;

    /**
     * @var
     */
    private $fromFullname;

    /**
     * @var string
     */
    private $to;

    /**
     * @var
     */
    private $toFullname;

    /**
     * @var float
     */
    private $mid;

    /**
     * @var float|null
     */
    private $buy = null;

    /**
     * @var float|null
     */
    private $sell = null;

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
    public function getFromFullname(): string
    {
        return $this->fromFullname;
    }

    /**
     * @param string $fromFullname
     * @return ExchangeRate
     */
    public function setFromFullname(string $fromFullname): self
    {
        $this->fromFullname = $fromFullname;

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
     * @return string
     */
    public function getToFullname(): string
    {
        return $this->toFullname;
    }

    /**
     * @param string $toFullname
     * @return ExchangeRate
     */
    public function setToFullname(string $toFullname): self
    {
        $this->toFullname = $toFullname;

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
        $this->mid = round($mid, 2) ?: null;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getBuy(): ?float
    {
        return $this->buy;
    }

    /**
     * @param float|null $buy
     * @return ExchangeRate
     */
    public function setBuy(?float $buy): self
    {
        $this->buy = round($buy, 2) ?: null;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getSell(): ?float
    {
        return $this->sell;
    }

    /**
     * @param float|null $sell
     * @return ExchangeRate
     */
    public function setSell(?float $sell): self
    {
        $this->sell = round($sell, 2) ?: null;

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
