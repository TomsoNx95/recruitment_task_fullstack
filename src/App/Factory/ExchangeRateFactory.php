<?php

declare(strict_types=1);

namespace App\Factory;

use App\Config\CurrencyConfig;
use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;
use App\Exception\NotSupportedCurrencyException;
use App\Helper\StringHelper;
use App\Strategy\ExchangeRate\ExchangeRateStrategyInterface;

/**
 * Class ExchangeRateFactory
 */
final class ExchangeRateFactory
{
    /**
     * @var array
     */
    private $exchangeRates = [];

    /**
     * @param ExchangeRateDTO $exchangeRateDTO
     * @param string $exchangeCurrency
     * @return ExchangeRateStrategyInterface
     * @throws NotSupportedCurrencyException
     */
    public function getStrategyByCurrency(ExchangeRateDTO $exchangeRateDTO, string $exchangeCurrency): ExchangeRateStrategyInterface
    {
        switch ($exchangeCurrency) {
            case CurrencyConfig::PLN:
                $strategyClassName = 'App\Strategy\ExchangeRate\Pln\\';
                break;
            default:
                throw new NotSupportedCurrencyException();
        }
        
        $strategyClassName .=
            StringHelper::onlyFirstCharacterUppercase($exchangeRateDTO->getFrom()) .
            'To' .
            StringHelper::onlyFirstCharacterUppercase($exchangeCurrency) .
            'Strategy'
        ;

        if (false === class_exists($strategyClassName)) {
            throw new NotSupportedCurrencyException();
        }

        return new $strategyClassName($exchangeRateDTO);
    }

    /**
     * @param ExchangeRate $exchangeRate
     * @return void
     */
    public function addExchangeRate(ExchangeRate $exchangeRate): void
    {
        $this->exchangeRates[] = $exchangeRate->toArray();
    }

    /**
     * @return array
     */
    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }
}
