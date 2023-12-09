<?php

declare(strict_types=1);

namespace App\Helper;

/**
 * Class StringHelper
 */
final class StringHelper
{
    /**
     * @param string $string
     * @return string
     */
    public static function onlyFirstCharacterUppercase(string $string): string
    {
        return ucfirst(strtolower($string));
    }
}
