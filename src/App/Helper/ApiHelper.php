<?php

declare(strict_types=1);

namespace App\Helper;

use App\Config\HttpMethodConfig;
use App\ValueObject\ApiHeader;
use Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface,
};
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ApiHelper
 */
final class ApiHelper
{
    /**
     * @param ApiHeader[] $headers
     * @return array
     */
    public static function headersToArray(array $headers): array
    {
        return empty($headers) ? [] : array_merge(...array_map(function ($header) {
            return [$header->getKey() => $header->getValue()];
        }, $headers));
    }

    /**
     * @param ResponseInterface $response
     * @param string $httpMethod
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public static function setResponseData(ResponseInterface $response, string $httpMethod): array
    {
        switch ($httpMethod) {
            case HttpMethodConfig::GET:
                return json_decode($response->getContent(), true);
            default:
                return [];
        }
    }
}
