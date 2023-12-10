<?php

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Traits\TraitDTO;
use App\ValueObject\ApiHeader;

/**
 * Class ApiRequestDTO
 */
final class ApiRequestDTO
{
    use TraitDTO;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $httpMethod;

    /**
     * @var ApiHeader[]
     */
    protected $headers;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @return ApiHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
