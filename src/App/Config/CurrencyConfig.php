<?php

declare(strict_types=1);

namespace App\Config;

/**
 * CurrencyConfig
 */
final class CurrencyConfig
{
    public const PLN = 'PLN';

    public const EUR = 'EUR';

    public const USD = 'USD';

    public const CZK = 'CZK';

    public const IDR = 'IDR';

    public const BRL = 'BRL';

    public const SUPPORTED_CURRIENCES = [
        self::PLN => self::PLN,
        self::EUR => self::EUR,
        self::USD => self::USD,
        self::CZK => self::CZK,
        self::IDR => self::IDR,
        self::BRL => self::BRL,
    ];
}
