<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate\Pln;

use App\Config\CurrencyConfig;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
use App\ValueObject\Currency;

/**
 * Class IdrToPlnStrategy
 */
final class IdrToPlnStrategy implements ExchangeRateStrategyInterface
{
    /**
     * @param float $value
     * @return Currency
     */
    public function buy(float $value): Currency
    {
        return new Currency(CurrencyConfig::PLN, $value);
    }

    /**
     * @param float $value
     * @return Currency
     */
    public function sell(float $value): Currency
    {
        return new Currency(CurrencyConfig::PLN, $value + 0.15);
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function supports(string $from, string $to): bool
    {
        return CurrencyConfig::IDR === $from && CurrencyConfig::PLN === $to;
    }
}
