<?php

declare(strict_types=1);

namespace App\Entity\Traits;

/**
 * Trait ObjectToArrayTrait
 */
trait ObjectToArrayTrait
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toReturn = [];

        $properties = get_object_vars($this);

        foreach ($properties as $k => $v) {
            $toReturn[$k] = $v;
        }

        return $toReturn;
    }
}
