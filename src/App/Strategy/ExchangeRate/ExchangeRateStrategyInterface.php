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
     * @return ExchangeRate
     */
    public function getCalculatedExchangeRate(): ExchangeRate;
}
