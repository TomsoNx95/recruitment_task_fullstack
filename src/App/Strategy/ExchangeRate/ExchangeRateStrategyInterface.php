<?php

declare(strict_types=1);

namespace App\Strategy\ExchangeRate;

use App\ValueObject\ExchangeRate;

/**
 * Interface ExchangeRateStrategyInterface
 */
interface ExchangeRateStrategyInterface
{
    /**
     * @return void
     */
    public function mid(): void;

    /**
     * @return void
     */
    public function buy(): void;

    /**
     * @return void
     */
    public function sell(): void;

    /**
     * @return void
     */
    public function from(): void;

    /**
     * @return void
     */
    public function to(): void;

    /**
     * @return void
     */
    public function date(): void;

    /**
     * @return ExchangeRate
     */
    public function getExchangeRate(): ExchangeRate;

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function supports(string $from, string $to): bool;
}
