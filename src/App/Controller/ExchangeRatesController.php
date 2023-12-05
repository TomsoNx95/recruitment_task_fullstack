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

   
    public function getExchangeRates(string $date = null): JsonResponse
    {
        $date = (new \DateTime())->format('Y-m-d');
       
        $currencies = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
        $results = [];
    
        $httpClient = HttpClient::create();
    
        foreach ($currencies as $currency) {
            // Pobierz dane dla dzisiejszej daty
            $today = new \DateTime();
            $formattedDate = $today->format('Y-m-d');

            $todayUrl = "https://api.nbp.pl/api/exchangerates/rates/A/{$currency}/{$formattedDate}";
            $todayResponse = $httpClient->request('GET', $todayUrl);
    
            // Pobierz dane dla dostarczonej daty
            $url = "https://api.nbp.pl/api/exchangerates/rates/A/{$currency}/{$date}";
            $response = $httpClient->request('GET', $url);
    
            try {
                if ($todayResponse->getStatusCode() === Response::HTTP_OK && $response->getStatusCode() === Response::HTTP_OK) {
                    $todayData = $todayResponse->toArray();
                    $data = $response->toArray();
    
                    // Przelicz kursy
                    $buyRate = null;
                    $sellRate = null;
    
                    if ($currency === 'EUR' || $currency === 'USD') {
                        $buyRate = round($data['rates'][0]['mid'] - self::EUR_BUY_RATE_DIFF, 2);
                        $sellRate = round($data['rates'][0]['mid'] + self::EUR_SELL_RATE_DIFF, 2);
                    } else {
                        // Dla innych walut
                        $sellRate = round($data['rates'][0]['mid'] + self::OTHER_SELL_RATE_DIFF, 2);
                    }
    
                    $results[] = [
                        'currency' => $data['currency'],
                        'code' => $data['code'],
                        'buyRate' => $buyRate,
                        'sellRate' => $sellRate,
                        'todayBuyRate' => round($todayData['rates'][0]['mid'], 2), 
                        'todaySellRate' => ceil($todayData['rates'][0]['mid'] * 100) / 100,
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