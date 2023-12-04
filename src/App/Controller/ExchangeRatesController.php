<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRatesController extends AbstractController
{
    // Przeliczniki
    const EUR_BUY_RATE_DIFF = 0.05;
    const EUR_SELL_RATE_DIFF = 0.07;
    const OTHER_SELL_RATE_DIFF = 0.15;

    /**
     * @Route("/api/exchange-rates", name="exchange_rates")
     */
    public function getExchangeRates(): JsonResponse
    {
        $currencies = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
        $results = [];

        $httpClient = HttpClient::create();

        foreach ($currencies as $currency) {
            $url = "https://api.nbp.pl/api/exchangerates/rates/A/{$currency}?format=json";

            try {
                $response = $httpClient->request('GET', $url);

                if ($response->getStatusCode() === Response::HTTP_OK) {
                    $data = $response->toArray();

                    // Przelicz kursy
                    $buyRate = null;
                    $totalBuyRate = null;
                    $sellRate = null;

                    if ($currency === 'EUR' || $currency === 'USD') {
                        $buyRate = round($data['rates'][0]['mid'] - self::EUR_BUY_RATE_DIFF, 2);
                        $totalBuyRate = round($data['rates'][0]['mid'], 2);
                        $sellRate = round($data['rates'][0]['mid'] + self::EUR_SELL_RATE_DIFF, 2);
                    } else {
                        // Dla innych walut
                        $sellRate = round($data['rates'][0]['mid'] + self::OTHER_SELL_RATE_DIFF, 3);
                    }

                    $results[] = [
                        'currency' => $data['currency'],
                        'code' => $data['code'],
                        'buyRate' => $buyRate,
                        'sellRate' => $sellRate,
                        'todayBuyRate' => round($data['rates'][0]['mid'], 2), 
                        'todaySellRate' => ceil($data['rates'][0]['mid'] * 100) / 100,
                    ];
                } else {
                    return $this->json(['error' => 'Unable to fetch exchange rates'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } catch (\Exception $e) {
                return $this->json(['error' => 'Connection error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->json(['rates' => $results]);
    }
}