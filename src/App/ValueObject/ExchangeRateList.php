<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Config\CurrencyConfig;
use App\Exception\NotSupportedCurrencyException;
use DateTimeImmutable;

/**
 * Class ExchangeRateList
 */
final class ExchangeRateList
{
    /**
     * @var array
     */
    private $rates;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @param array $rates
     * @param DateTimeImmutable $date
     */
    public function __construct(
        array $rates,
        DateTimeImmutable $date
    ) {
        $this->rates = $rates;
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param string $exchangeCurrency
     * @return array
     * @throws NotSupportedCurrencyException
     */
    public function getSupportedRateList(string $exchangeCurrency): array
    {
        $supportedCurrencies = CurrencyConfig::SUPPORTED_CURRENCIES[$exchangeCurrency] ?? [];

        if (empty($supportedCurrencies)) {
            throw new NotSupportedCurrencyException();
        }

        return array_intersect_key(
            $this->rates,
            array_flip($supportedCurrencies)
        );
    }
}
