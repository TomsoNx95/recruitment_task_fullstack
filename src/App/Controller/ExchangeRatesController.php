<?php

declare(strict_types=1);

namespace App\Controller;

use App\Config\CurrencyConfig;
use App\Exception\NotSupportedCurrencyException;
use App\Service\ExchangeRateService;
use DateTimeImmutable;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Request,
    Response,
};
use Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface,
};

/**
 * Class ExchangeRatesController
 */
final class ExchangeRatesController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ExchangeRateService
     */
    private $exchangeRateService;

    /**
     * @param LoggerInterface $logger
     * @param ExchangeRateService $exchangeRateService
     */
    public function __construct(
        LoggerInterface $logger,
        ExchangeRateService $exchangeRateService
    ) {
        $this->logger = $logger;
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * ApiLink: /api/exchange-rate-list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $date = $request->get('date') ?? null;
        $exchangeCurrency = $request->get('exchangeCurrency') ?? CurrencyConfig::PLN;

        try {
            return JsonResponse::create([
                'data' => [
                    'status' => true,
                    'exchange_rates_today' => $this->exchangeRateService->getExchangeRateListToday(
                        $exchangeCurrency
                    ),
                    'exchange_rates_by_date' => $date
                        ? $this->exchangeRateService->getExchangeRateListByDate(
                            new DateTimeImmutable($date),
                            $exchangeCurrency
                        )
                        : [],
                ],
                'message' => 'Exchange rate list have been retrieved successfully.',
                'code' => Response::HTTP_OK,
            ], Response::HTTP_OK, ['Content-type' => 'application/json']);
        } catch (Exception|
            ClientExceptionInterface|
            RedirectionExceptionInterface|
            ServerExceptionInterface|
            TransportExceptionInterface|
            NotSupportedCurrencyException $exception) {

            $this->logger->error(
                'File: ' . $exception->getFile() . PHP_EOL .
                'Line: ' . $exception->getLine() . PHP_EOL .
                'Message: ' . $exception->getMessage() . PHP_EOL .
                'Code: ' . $exception->getCode()
            );

            return JsonResponse::create([
                'data' => [
                    'status' => false,
                ],
                'message' => $exception->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
