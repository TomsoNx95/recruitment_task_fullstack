<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * Currency
 */
class Currency
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $value;

    /**
     * @param string $name
     * @param float $value
     */
    public function __construct(string $name, float $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
