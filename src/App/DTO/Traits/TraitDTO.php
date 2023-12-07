<?php

declare(strict_types=1);

namespace App\DTO\Traits;

/**
 * Trait TraitDTO
 */
trait TraitDTO
{
    /**
     * @var array
     */
    private $data;

    /**
     * Constructor of TraitDTO
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->createInstance();
    }

    /**
     * @return void
     */
    private function createInstance(): void
    {
        foreach ($this->data as $key => $value) {
            $this->{$key} = $value;
        }

        unset($this->data);
    }
}
