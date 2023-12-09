<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate;

use App\Entity\ExchangeRate;

/**
 * Interface ExchangeRateStrategyInterface
 */
interface ExchangeRateStrategyInterface
{
    /**
     * @return \App\Entity\ExchangeRate
     */
    public function getCalculatedExchangeRate(): ExchangeRate;

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function supports(string $from, string $to): bool;
}
