<?php

declare(strict_types=1);

namespace App\Factory;

use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
use App\ValueObject\Currency;

/**
 * Class ExchangeRateFactory
 */
class ExchangeRateFactory
{
    /**
     * @var ExchangeRateStrategyInterface
     */
    private $exchangeRateStrategy;

    /**
     * @var Currency[]
     */
    private $currencies = [];

    /**
     * @param ExchangeRateStrategyInterface $exchangeRateStrategy
     */
    public function __construct(ExchangeRateStrategyInterface $exchangeRateStrategy)
    {
        $this->exchangeRateStrategy = $exchangeRateStrategy;
    }

    /**
     * @param Currency $currency
     * @return void
     */
    public function addCurrency(Currency $currency): void
    {
        $this->currencies[] = $currency;
    }
}
