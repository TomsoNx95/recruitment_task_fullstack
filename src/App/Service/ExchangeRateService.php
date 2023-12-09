<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\CurrencyConfig;
use App\Exception\NotSupportedCurrencyException;
use App\Factory\ExchangeRateFactory;
use App\Helper\ArrayHelper;
use App\ValueObject\ExchangeRate;
use Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface,
};
use DateTimeImmutable;

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
     * @var ExchangeRateFactory
     */
    private $exchangeRateFactory;

    /**
     * @param ApiNbpService $apiNbpService
     * @param ExchangeRateFactory $exchangeRateFactory
     */
    public function __construct(
        ApiNbpService $apiNbpService,
        ExchangeRateFactory $exchangeRateFactory
    ) {
        $this->apiNbpService = $apiNbpService;
        $this->exchangeRateFactory = $exchangeRateFactory;
    }

    /**
     * @return ExchangeRate[]
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws NotSupportedCurrencyException
     */
    public function getExchangeRateListToday(string $exchangeCurrency = CurrencyConfig::PLN): array
    {
        $rateListToday = ArrayHelper::prepareArrayByField(
            $this->apiNbpService->getExchangeRatesTableAToday()[0]['rates'] ?? [],
            'code'
        );

        return $this->getExchangeRateList(
            $rateListToday,
            $exchangeCurrency
        );
    }

    /**
     * @return ExchangeRate[]
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws NotSupportedCurrencyException
     */
    public function getExchangeRateListByDate(DateTimeImmutable $date, string $exchangeCurrency = CurrencyConfig::PLN): array
    {
        $rateListToday = ArrayHelper::prepareArrayByField(
            $this->apiNbpService->getExchangeRatesTableAByDate($date)[0]['rates'] ?? [],
            'code'
        );

        return $this->getExchangeRateList(
            $rateListToday,
            $exchangeCurrency
        );
    }

    /**
     * @param array $rateList
     * @param string $exchangeCurrency
     * @return ExchangeRate[]
     * @throws NotSupportedCurrencyException
     */
    private function getExchangeRateList(array $rateList, string $exchangeCurrency): array
    {
        $supportedCurrencies = CurrencyConfig::SUPPORTED_CURRENCIES[$exchangeCurrency] ?? [];

        if (empty($supportedCurrencies)) {
            throw new NotSupportedCurrencyException();
        }

        $rateList = array_intersect_key(
            $rateList,
            array_flip($supportedCurrencies)
        );

        foreach ($rateList as $currency => $rate) {
            $this->exchangeRateFactory->addExchangeRate(
                $this->exchangeRateFactory
                    ->getStrategyByCurrency($currency)
                    ->getExchangeRate()
            );
        }
        dd($this->exchangeRateFactory->getExchangeRates());
        return $this->exchangeRateFactory->getExchangeRates();
    }
}
