<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate;

use App\ValueObject\Currency;

/**
 * Interface ExchangeRateStrategyInterface
 */
interface ExchangeRateStrategyInterface
{
    /**
     * @param float $value
     * @return Currency
     */
    public function buy(float $value): Currency;

    /**
     * @param float $value
     * @return Currency
     */
    public function sell(float $value): Currency;

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function supports(string $from, string $to): bool;
}
