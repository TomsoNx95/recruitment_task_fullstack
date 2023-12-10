<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\CurrencyConfig;
use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;
use App\Exception\NotSupportedCurrencyException;
use App\Factory\ExchangeRateFactory;
use App\Helper\ArrayHelper;
use App\ValueObject\ExchangeRateList;
use DateTimeImmutable;
use Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface,
};
use Exception;

/**
 * Class ExchangeRateService
 */
final class ExchangeRateService
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
     * @param string $exchangeCurrency
     * @return array
     * @throws ClientExceptionInterface
     * @throws NotSupportedCurrencyException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function getExchangeRateListToday(string $exchangeCurrency = CurrencyConfig::PLN): array
    {
        $fetchedRateListApi = $this->apiNbpService->getExchangeRatesTableAToday();

        $rateListToday = new ExchangeRateList(
            ArrayHelper::prepareArrayByField(
                $fetchedRateListApi[0]['rates'] ?? [],
                'code'
            ),
            new DateTimeImmutable($fetchedRateListApi[0]['effectiveDate'] ?? '')
        );

        return $this->getExchangeRateList(
            $rateListToday,
            $exchangeCurrency
        );
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws NotSupportedCurrencyException
     * @throws Exception
     */
    public function getExchangeRateListByDate(DateTimeImmutable $date, string $exchangeCurrency = CurrencyConfig::PLN): array
    {
        $fetchedRateListApi = $this->apiNbpService->getExchangeRatesTableAByDate($date);

        $rateListByDate = new ExchangeRateList(
            ArrayHelper::prepareArrayByField(
                $fetchedRateListApi[0]['rates'] ?? [],
                'code'
            ),
            new DateTimeImmutable($fetchedRateListApi[0]['effectiveDate'] ?? '')
        );

        return $this->getExchangeRateList(
            $rateListByDate,
            $exchangeCurrency
        );
    }

    /**
     * @param ExchangeRateList $rateList
     * @param string $exchangeCurrency
     * @return array
     * @throws NotSupportedCurrencyException
     */
    private function getExchangeRateList(ExchangeRateList $rateList, string $exchangeCurrency): array
    {
        $supportedRateList = $rateList->getSupportedRateList($exchangeCurrency);

        $exchangeRateFactory = new ExchangeRateFactory();

        foreach ($supportedRateList as $currency => $rate) {
            $exchangeRateFactory->addExchangeRate(
                $exchangeRateFactory
                    ->getStrategyByCurrency(
                        new ExchangeRateDTO([
                            'midValue' => $rate['mid'],
                            'date' => $rateList->getDate(),
                            'from' => $currency,
                            'fromFullname' => $rate['currency']
                        ]),
                        $exchangeCurrency
                    )
                    ->getCalculatedExchangeRate()
            );

            unset($supportedRateList[$currency]);
        }

        return $exchangeRateFactory->getExchangeRates();
    }
}
