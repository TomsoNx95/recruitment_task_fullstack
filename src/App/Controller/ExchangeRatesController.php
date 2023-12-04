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
                    $results[] = [
                        'currency' => $data['currency'],
                        'code' => $data['code'],
                        'mid' => $data['rates'][0]['mid'],
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