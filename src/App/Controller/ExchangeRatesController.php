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
    // Waluty, jeśli chcemy wyświetlać inne waluty, modyfikujemy w tym miejscu
    const CURRIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

    // Przeliczniki
    const EUR_BUY_RATE_DIFF = 0.05;
    const EUR_SELL_RATE_DIFF = 0.07;
    const OTHER_SELL_RATE_DIFF = 0.15;

    public function getExchangeRates(string $date = null): JsonResponse
    {
        // Jeśli nie podano daty, użyj bieżącej daty
        if (empty($date)) {
            $date = (new \DateTime())->format('Y-m-d');
        }

        $results = [];
        $httpClient = HttpClient::create();

        // Z powodu braku endpointa, w którym możemy podać tablicę z walutami,
        // musimy dla każdej waluty wykonać osobne zapytanie.
        // Moglibyśmy dzisiejsze kursy (today) przechowywać w cache, aby unikać nadmiernych zapytań do API,
        // i resetować cache północy każdego dnia.

        // Iteracja przez wszystkie zdefiniowane waluty
        foreach (self::CURRIES as $currency) {
            // Pobierz dane dla dostarczonej daty. Jeśli date to 'today', pobierz aktualne kursy.
            $url = "https://api.nbp.pl/api/exchangerates/rates/A/{$currency}/{$date}";
            if ($date === 'today') {
                $url = "https://api.nbp.pl/api/exchangerates/rates/A/{$currency}/today";
            }

            // Wykonaj zapytanie HTTP do API NBP
            $response = $httpClient->request('GET', $url);

            try {
                // Sprawdź, czy odpowiedź ma status HTTP 200 OK
                if ($response->getStatusCode() === Response::HTTP_OK) {
                    // Pobierz dane z odpowiedzi jako tablicę
                    $data = $response->toArray();

                    // Przelicz kursy
                    $buyRate = null;
                    $sellRate = null;

                    // Dla EUR i USD zastosuj przeliczniki
                    if ($currency === 'EUR' || $currency === 'USD') {
                        $buyRate = $data['rates'][0]['mid'] - self::EUR_BUY_RATE_DIFF;
                        $sellRate = $data['rates'][0]['mid'] + self::EUR_SELL_RATE_DIFF;
                    } else {
                        // Dla innych walut zastosuj inny przelicznik
                        $sellRate = $data['rates'][0]['mid'] + self::OTHER_SELL_RATE_DIFF;
                    }

                    // Dodaj dane do wyników
                    $results[] = [
                        'currency' => $data['currency'],
                        'code' => $data['code'],
                        'buyRate' => $buyRate,
                        'sellRate' => $sellRate
                    ];
                } else {
                    // Jeśli status HTTP nie jest 200 OK, zwróć błąd
                    return $this->json(['error' => 'Unable to fetch exchange rates'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } catch (\Exception $e) {
                // W przypadku błędu połączenia, zwróć błąd
                return $this->json(['error' => 'Connection error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // Zwróć dane kursów w formie JSON
        return $this->json(['rates' => $results]);
    }
}