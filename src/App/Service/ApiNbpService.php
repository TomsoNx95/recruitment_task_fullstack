<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\HttpMethodConfig;
use App\DTO\ApiRequestDTO;
use App\Helper\ApiHelper;
use Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface,
};
use DateTimeImmutable;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ApiNbpService
 */
final class ApiNbpService
{
    /**
     * @var string
     */
    private $apiNbpUrl;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @param string $apiNbpUrl
     * @param HttpClientInterface $httpClient
     */
    public function __construct(
        string $apiNbpUrl,
        HttpClientInterface $httpClient
    ) {
        $this->apiNbpUrl = $apiNbpUrl;
        $this->httpClient = $httpClient;
    }

    /**
     * @param ApiRequestDTO $apiRequestDTO
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function sendRequest(ApiRequestDTO $apiRequestDTO): array
    {
        return ApiHelper::setResponseData(
            $this->httpClient->request(
                $apiRequestDTO->getHttpMethod(),
                $this->apiNbpUrl . ltrim($apiRequestDTO->getUrl(), '/'),
                ApiHelper::headersToArray($apiRequestDTO->getHeaders())
            ),
            $apiRequestDTO->getHttpMethod()
        );
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getExchangeRatesTableAToday(): array
    {
        return $this->sendRequest(
            new ApiRequestDTO([
                'url' => 'exchangerates/tables/a',
                'httpMethod' => HttpMethodConfig::GET,
                'headers' => []
            ])
        );
    }

    /**
     * @param DateTimeImmutable $date
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getExchangeRatesTableAByDate(DateTimeImmutable $date): array
    {
        return $this->sendRequest(
            new ApiRequestDTO([
                'url' => 'exchangerates/tables/a/' . $date->format('Y-m-d'),
                'httpMethod' => HttpMethodConfig::GET,
                'headers' => []
            ])
        );
    }
}
