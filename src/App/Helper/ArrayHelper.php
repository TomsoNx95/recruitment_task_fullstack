<?php

declare(strict_types=1);

namespace App\Helper;

/**
 * Class ArrayHelper
 */
final class ArrayHelper
{
    /**
     * @param array $array
     * @param string $field
     * @return array
     */
    public static function prepareArrayByField(array $array, string $field): array
    {
        if (empty($array)) {
            return [];
        }

        $preparedArray = [];

        foreach ($array as $key => $value) {
            if (!isset($preparedArray[$value[$field]])) {
                $preparedArray[$value[$field]] = $value;
            }

            unset($array[$key]);
        }

        return $preparedArray;
    }
}
