<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;
use App\Strategy\ExchangeRate\Pln\UsdToPlnStrategy;

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
     * @param ExchangeRateDTO $exchangeRateDTO
     * @param string $currency
     * @return ExchangeRateStrategyInterface
     */
    public function getStrategyByCurrency(ExchangeRateDTO $exchangeRateDTO, string $currency): ExchangeRateStrategyInterface
    {

//        switch ($currency) {
//            case CurrencyConfig::PLN:
//                return new
//        }

        return (new UsdToPlnStrategy($exchangeRateDTO));
    }

    /**
     * @param \App\Entity\ExchangeRate $exchangeRate
     * @return void
     */
    public function addExchangeRate(ExchangeRate $exchangeRate): void
    {
        $this->exchangeRates[] = $exchangeRate;
    }

    /**
     * @return \App\Entity\ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }
}
