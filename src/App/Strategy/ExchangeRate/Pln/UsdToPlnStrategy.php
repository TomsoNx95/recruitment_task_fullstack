<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate\Pln;

use App\Config\CurrencyConfig;
use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
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
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $fromFullname;

    /**
     * @var ExchangeRate
     */
    private $exchangeRate;

    /**
     * @param ExchangeRateDTO $exchangeRate
     */
    public function __construct(ExchangeRateDTO $exchangeRate)
    {
        $this->midValue = $exchangeRate->getMidValue();
        $this->date = $exchangeRate->getDate();
        $this->fromFullname = $exchangeRate->getFromFullname();

        $this->exchangeRate = new ExchangeRate();
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
    public function getCalculatedExchangeRate(): ExchangeRate
    {
        return $this
            ->exchangeRate
            ->setMid($this->midValue)
            ->setBuy($this->midValue - 0.05)
            ->setSell($this->midValue + 0.07)
            ->setFrom(CurrencyConfig::USD)
            ->setFromFullname($this->fromFullname)
            ->setTo(CurrencyConfig::PLN)
            ->setToFullname(CurrencyConfig::PLN_FULLNAME)
            ->setDate($this->date)
        ;
    }
}
