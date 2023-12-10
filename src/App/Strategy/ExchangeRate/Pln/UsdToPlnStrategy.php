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
    private const BUY = 0.05;

    private const SELL = 0.07;

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
     * @return ExchangeRate
     */
    public function getCalculatedExchangeRate(): ExchangeRate
    {
        return $this
            ->exchangeRate
            ->setMid($this->midValue)
            ->setBuy($this->midValue - self::BUY)
            ->setSell($this->midValue + self::SELL)
            ->setFrom(CurrencyConfig::USD)
            ->setFromFullname($this->fromFullname)
            ->setTo(CurrencyConfig::PLN)
            ->setToFullname(CurrencyConfig::PLN_FULLNAME)
            ->setDate($this->date)
        ;
    }
}
