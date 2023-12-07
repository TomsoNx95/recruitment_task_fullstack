<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRatesController extends AbstractController
{
    // Url do ktorego będziemy robić strzały w celu pobrania kursu walut
    const urlApiNbp = 'https://api.nbp.pl';

    // Waluty, jeśli chcemy wyświetlać inne waluty, modyfikujemy w tym miejscu
    // Moglibyśmy to wyciągnąc do zmiennych środowiskowych, aby wygodnie modyfikować te wartości
    const CURRIES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];

    // Przeliczniki dla walut
    // Tak samo można by to wyciągnać do zmiennych środowiskowych
    const EUR_USD_BUY_RATE_DIFF = 0.05;     // Dla euro oraz USD
    const EUR_SELL_RATE_DIFF = 0.07;    // Dla euro oraz USD
    const OTHER_SELL_RATE_DIFF = 0.15;  // Dla reszty walut

    /**
     * @Route("/exchange-rates/{date}", name="exchange_rates", defaults={"date": null}, methods={"GET"})
     *
     * Funkcja odpowiadająca za pobranie kursów walut dla podanej daty lub dzisiejszych kursów.
     *
     * @param HttpClientInterface $httpClient   //Wstrzykujemy zależność HttpClientInterface
     * @param string|null $date                 //Data powinna być w formacie r-m-d, lub today, aby pobrać dzisiejszy kurs
     * @return JsonResponse
     */
    public function getExchangeRates(HttpClientInterface $httpClient, string $date = null): JsonResponse
    {
        // Jeśli nie podano daty, użyj bieżącej daty
        if (empty($date)) {
            $date = (new \DateTime())->format('Y-m-d');
        }

        // Tworzymy pustą tablicę przed zapisem danych aby, uniknac problemu gdy Api zwróci 0 kursów walut..
        $results = [];

        // Iteracja przez wszystkie zdefiniowane waluty
        foreach (self::CURRIES as $currency) {

            // Tworzymy odpowiedni url do strzału w zależności od parametru w url na naszej stronie.
            $action = '/api/exchangerates/rates/A/';
            $url = $this->buildUrl($currency, $date, $action);

            // Robimy strzał do Api Nbp
            $response = $httpClient->request('GET', $url);

            try {
                if ($response->getStatusCode() === Response::HTTP_OK) {
                    // Zamieniamy odpowiedź ze strzału na tablicę
                    $responseData = $response->toArray();

                    // Liczymy waluty po odpowiednich przelicznikach, możemy wpisać jako 3 parametr false, aby nie zaorkąglać 4 miejsc po przecinku....
                    $results[] = $this->calculateRates($responseData, $currency);
                
                } else {
                    return $this->json(['error' => 'Unable to fetch exchange rates'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } catch (\Exception $e) {
                return $this->json(['error' => 'Connection error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        
        return $this->json(['rates' => $results]);
    }


// Tutaj są funkcje odpowiedzialne za logikę biznesową. Powinniśmy je przenieść do serwisu.
// Miałem mało czasu, aby naprawić te serwisy, więc tymczasowo przeniosłem to na dół
// @todo Napraw serwisy i przenieś poniższą logikę biznesową do odpowiedniego serwisu.

    /**
     * Buduje URL do API NBP w zależności od podanej waluty i daty.
     *
     * @param string $currency
     * @param string $date
     * @return string
     */
    private function buildUrl(string $currency, string $date, string $action): string
    {
        if ($date === 'today') {
            $url = sprintf('%s%s%s/today', self::urlApiNbp, $action, $currency);
        } else {
            $url = sprintf('%s%s%s/%s', self::urlApiNbp, $action, $currency, $date);
        }
    
        return $url;
    }

    /**
     * Przelicza kursy na podstawie danych z API NBP.
     *
     * @param array    $data
     * @param string   $currency
     * @param bool     $round = false  Może przyjąć false jeśli nie chcemy zaokraglać
     * @return array
     */
    private function calculateRates(array $data, string $currency, bool $round = true): array
    {
        $buyRate = null;
        $sellRate = null;

        if ($this->isEURorUSD($currency)) {
            $buyRate = $data['rates'][0]['mid'] - self::EUR_USD_BUY_RATE_DIFF;
            $sellRate = $data['rates'][0]['mid'] + self::EUR_SELL_RATE_DIFF;
        } else {
            $sellRate = $data['rates'][0]['mid'] + self::OTHER_SELL_RATE_DIFF;
        }

        // Warunek sprawdzający, czy należy zaokrąglić 4 miejsca po przecinku, zawsze musimy otrzymywać 4 liczby po przecinku
        if ($round) {
            $buyRate = $buyRate !== null ? number_format(round($buyRate, 4), 4, '.', '') : null;
            $sellRate = $sellRate !== null ? number_format(round($sellRate, 4), 4, '.', '') : null;
        }

        $result = [
            'currency' => $data['currency'],
            'code' => $data['code'],
            'buyRate' => (string)$buyRate,
            'sellRate' => (string)$sellRate
        ];
        
        return $result;
    }

    /**
     * Sprawdza, czy podana waluta to EUR lub USD.
     *
     * @param string $currency
     * @return bool
     */
    private function isEURorUSD(string $currency): bool
    {
        return $currency === 'EUR' || $currency === 'USD';
    }
}