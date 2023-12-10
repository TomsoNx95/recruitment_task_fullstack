<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

/**
 * Class NotSupportedCurrencyException
 */
final class NotSupportedCurrencyException extends Exception
{
    private const CODE = 3001;
    private const MESSAGE = 'Currency is not supported to getting exchange rates.';

    /**
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
