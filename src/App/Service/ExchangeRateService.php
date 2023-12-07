<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface,
};

/**
 * Class ExchangeRateService
 */
class ExchangeRateService
{
    /**
     * @var ApiNbpService
     */
    private $apiNbpService;

    /**
     * @param ApiNbpService $apiNbpService
     */
    public function __construct(ApiNbpService $apiNbpService)
    {
        $this->apiNbpService = $apiNbpService;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getRateList(): array
    {
        $rateList = $this->apiNbpService->getExchangeRatesTableAToday()[0]['rates'] ?? [];

        foreach ($rateList as $rate) {
            
        }
    }
}
