<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate\Pln;

use App\Config\CurrencyConfig;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
use App\ValueObject\ExchangeRate;
use DateTimeImmutable;

/**
 * Class UsdToPlnStrategy
 */
final class UsdToPlnStrategy implements ExchangeRateStrategyInterface
{
    /**
     * @var float
     */
    private $midValue;

    /**
     * @var ExchangeRate
     */
    private $exchangeRate;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @param float $midValue
     * @param DateTimeImmutable $date
     */
    public function __construct(float $midValue, DateTimeImmutable $date)
    {
        $this->midValue = $midValue;
        $this->date = $date;
        $this->exchangeRate = new ExchangeRate();
    }

    /**
     * @return void
     */
    public function mid(): void
    {
        $this->exchangeRate->setMid($this->midValue);
    }

    /**
     * @return void
     */
    public function buy(): void
    {
        $this->exchangeRate->setBuy($this->midValue - 0.05);
    }

    /**
     * @return void
     */
    public function sell(): void
    {
        $this->exchangeRate->setSell($this->midValue + 0.07);
    }

    /**
     * @return void
     */
    public function from(): void
    {
        $this->exchangeRate->setFrom(CurrencyConfig::USD);
    }

    /**
     * @return void
     */
    public function to(): void
    {
        $this->exchangeRate->setTo(CurrencyConfig::PLN);
    }

    /**
     * @return void
     */
    public function date(): void
    {
        $this->exchangeRate->setDate($this->date);
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function supports(string $from, string $to): bool
    {
        return CurrencyConfig::USD === $from && CurrencyConfig::PLN === $to;
    }

    /**
     * @return ExchangeRate
     */
    public function getExchangeRate(): ExchangeRate
    {
        return $this->exchangeRate;
    }
}
