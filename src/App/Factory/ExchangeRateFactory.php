<?php

declare(strict_types=1);

namespace App\Factory;

use App\Config\CurrencyConfig;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
use App\Strategy\ExchangeRate\Pln\UsdToPlnStrategy;
use App\ValueObject\ExchangeRate;
use DateTimeImmutable;

/**
 * Class ExchangeRateFactory
 */
class ExchangeRateFactory
{
    /**
     * @var ExchangeRate[]
     */
    private $exchangeRates = [];

    /**
     * @param string $currency
     * @return ExchangeRateStrategyInterface
     */
    public function getStrategyByCurrency(string $currency): ExchangeRateStrategyInterface
    {

//        switch ($currency) {
//            case CurrencyConfig::PLN:
//                return new
//        }
        return (new UsdToPlnStrategy(20, new DateTimeImmutable()));
    }

    /**
     * @param ExchangeRate $exchangeRate
     * @return void
     */
    public function addExchangeRate(ExchangeRate $exchangeRate): void
    {
        $this->exchangeRates[] = $exchangeRate;
    }

    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }
}
