<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate\Pln;

use App\Config\CurrencyConfig;
use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
use DateTimeImmutable;

/**
 * Class IdrToPlnStrategy
 */
final class IdrToPlnStrategy implements ExchangeRateStrategyInterface
{
    private const BUY = null;

    private const SELL = 0.15;

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
            ->setBuy(self::BUY)
            ->setSell($this->midValue + self::SELL)
            ->setFrom(CurrencyConfig::IDR)
            ->setFromFullname($this->fromFullname)
            ->setTo(CurrencyConfig::PLN)
            ->setToFullname(CurrencyConfig::PLN_FULLNAME)
            ->setDate($this->date)
        ;
    }
}
