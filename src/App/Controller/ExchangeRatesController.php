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
 
    public function getExchangeRates(): JsonResponse
    {
        $url = 'https://api.nbp.pl/api/exchangerates/rates/A/USD?format=json';
        // Utwórz instancję HttpClient
        $httpClient = HttpClient::create();


        try {
            // Wykonaj zapytanie HTTP do API NBP
            $response = $httpClient->request('GET', $url);

            // Sprawdź, czy odpowiedź jest poprawna (status 200)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                // Pobierz dane JSON z odpowiedzi
                $data = $response->toArray();

                // Zwróć dane w formie odpowiedzi JSON
                return $this->json($data);
            } else {
                // Zwróć odpowiedź błędu, jeśli status nie jest 200
                return $this->json(['error' => 'Unable to fetch exchange rates'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            // Zwróć odpowiedź błędu w przypadku problemów z połączeniem
            return $this->json(['error' => 'Connection error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}