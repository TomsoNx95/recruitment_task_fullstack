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
     * @return ExchangeRate[]
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
     * @return ExchangeRate[]
     * @throws NotSupportedCurrencyException
     */
    private function getExchangeRateList(ExchangeRateList $rateList, string $exchangeCurrency): array
    {
        $supportedCurrencies = CurrencyConfig::SUPPORTED_CURRENCIES[$exchangeCurrency] ?? [];

        if (empty($supportedCurrencies)) {
            throw new NotSupportedCurrencyException();
        }

        $supportedRateList = array_intersect_key(
            $rateList->getRates(),
            array_flip($supportedCurrencies)
        );

        foreach ($supportedRateList as $currency => $rate) {
            $this->exchangeRateFactory->addExchangeRate(
                $this
                    ->exchangeRateFactory
                    ->getStrategyByCurrency(
                        new ExchangeRateDTO([
                            'midValue' => $rate['mid'],
                            'date' => $rateList->getDate(),
                            'fromFullname' => $rate['currency']
                        ]),
                        $exchangeCurrency
                    )
                    ->getCalculatedExchangeRate()
            );

            unset($supportedRateList[$currency]);
        }
        dd($this->exchangeRateFactory->getExchangeRates());
        return $this->exchangeRateFactory->getExchangeRates();
    }
}
