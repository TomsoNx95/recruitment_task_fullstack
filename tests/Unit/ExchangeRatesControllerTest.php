<?php

declare(strict_types=1);

namespace Unit\ExchangeRatesController;

use App\Controller\ExchangeRatesController;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;

class ExchangeRatesControllerTest extends TestCase
{

    /**
     * Testuje metodę buildUrl dla kontrolera ExchangeRatesController.
     */
    public function testBuildUrl(): void
    {
        // Tworzymy instancję kontrolera
        $controller = new ExchangeRatesController();

        // Domyślne wartości dla testu
        $currency = 'EUR';
        $date = '2023-01-01';
        $action = '/api/exchangerates/rates/A/';

        // Wywołujemy prywatną metodę buildUrl na instancji kontrolera
        $result = $this->invokePrivateMethod($controller, 'buildUrl', [$currency, $date, $action]);

        // Oczekiwany wynik dla daty różnej niż 'today'
        $expectedResult = 'https://api.nbp.pl/api/exchangerates/rates/A/EUR/2023-01-01';

        // Porównujemy wyniki za pomocą asercji
        $this->assertEquals($expectedResult, $result, 'Should return the correct URL for a specific date');

        // Zmieniamy datę na 'today'
        $date = 'today';

        // Ponownie wywołujemy prywatną metodę buildUrl na instancji kontrolera
        $result = $this->invokePrivateMethod($controller, 'buildUrl', [$currency, $date, $action]);

        // Oczekiwany wynik dla daty 'today'
        $expectedResult = 'https://api.nbp.pl/api/exchangerates/rates/A/EUR/today';

        // Ponownie porównujemy wyniki za pomocą asercji
        $this->assertEquals($expectedResult, $result, 'Should return the correct URL for "today"');
    }

    /**
     * Wywołuje prywatną metodę na danej instancji obiektu.
     *
     * @param object $object
     * @param string $methodName
     * @param array $parameters
     * @return mixed
     */
    private function invokePrivateMethod(object $object, string $methodName, array $parameters = [])
    {
        $method = new \ReflectionMethod(get_class($object), $methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
  
    /**
     * Testuje metodę calculateRates dla kontrolera ExchangeRatesController.
     */
    public function testCalculateRates(): void
    {
        // Tworzymy instancję kontrolera
        $controller = new ExchangeRatesController();

        // Dane testowe dla kursu walutowego EUR
        $data = [
            'rates' => [
                [
                    'mid' => 4.50,
                ],
            ],
            'currency' => 'EUR',
            'code' => 'EUR',
        ];

        // Uzyskujemy dostęp do prywatnej metody calculateRates za pomocą refleksji
        $calculateRatesMethod = new \ReflectionMethod(ExchangeRatesController::class, 'calculateRates');
        $calculateRatesMethod->setAccessible(true);

        // Wywołujemy prywatną metodę calculateRates na instancji kontrolera
        $result = $calculateRatesMethod->invokeArgs($controller, [$data, 'EUR']);

        // Oczekiwany wynik dla kursu walutowego EUR
        $expectedResult = [
            'currency' => 'EUR',
            'code' => 'EUR',
            'buyRate' => '4.4500',
            'sellRate' => '4.5700',
        ];

        // Porównujemy wyniki za pomocą asercji
        $this->assertEquals($expectedResult, $result, 'Should return the correct rates for "EUR"');

        // Zmieniamy walutę na GBP
        $data['currency'] = 'GBP';

        // Ponownie wywołujemy prywatną metodę calculateRates na instancji kontrolera
        $result = $calculateRatesMethod->invokeArgs($controller, [$data, 'GBP']);

        // Oczekiwany wynik dla kursu walutowego GBP
        $expectedResult = [
            'currency' => 'GBP',
            'code' => 'EUR',
            'buyRate' => null,
            'sellRate' => '4.6500',
        ];

        // Ponownie porównujemy wyniki za pomocą asercji
        $this->assertEquals($expectedResult, $result, 'Should return the correct rates for "GBP"');
    }

    /**
     * Testuje prywatną metodę isEURorUSD klasy ExchangeRatesController.
     */
    public function testIsEURorUSD(): void
    {
        // Tworzymy instancję kontrolera, który będzie testowany
        $controller = new ExchangeRatesController();

        // Tworzymy obiekt ReflectionMethod, aby uzyskać dostęp do prywatnej metody isEURorUSD
        $isEURorUSDMethod = new \ReflectionMethod(ExchangeRatesController::class, 'isEURorUSD');

        // Ustawiamy dostęp do prywatnej metody
        $isEURorUSDMethod->setAccessible(true);

        // Sprawdzamy, czy dla waluty EUR metoda zwraca true
        $result1 = $isEURorUSDMethod->invokeArgs($controller, ['EUR']);
        $this->assertTrue($result1, 'Should return true for "EUR"');

        // Sprawdzamy, czy dla waluty USD metoda zwraca true
        $result2 = $isEURorUSDMethod->invokeArgs($controller, ['USD']);
        $this->assertTrue($result2, 'Should return true for "USD');

        // Sprawdzamy, czy dla innej waluty metoda zwraca false
        $result3 = $isEURorUSDMethod->invokeArgs($controller, ['GBP']);
        $this->assertFalse($result3, 'Should return false for "GBP"');
    }


    /**
     * Testuje prywatną metodę isValidYear klasy ExchangeRatesController.
     */
    public function testIsValidYear(): void
    {
        // Tworzymy instancję kontrolera, który będzie testowany
        $controller = new ExchangeRatesController();

        // Tworzymy obiekt ReflectionMethod, aby uzyskać dostęp do prywatnej metody isValidYear
        $isValidYearMethod = new \ReflectionMethod(ExchangeRatesController::class, 'isValidYear');

        // Ustawiamy dostęp do prywatnej metody
        $isValidYearMethod->setAccessible(true);

        // Bieżący rok
        $currentYear = (new \DateTime())->format('Y');

        // Wywołujemy prywatną metodę z bieżącym rokiem
        $result1 = $isValidYearMethod->invokeArgs($controller, [$currentYear]);

        // Wywołujemy prywatną metodę z rokiem o jeden mniejszym niż bieżący
        $result2 = $isValidYearMethod->invokeArgs($controller, [(string)((int)$currentYear - 1)]);

        // Sprawdzamy, czy dla bieżącego roku isValidYear zwraca true
        $this->assertTrue($result1);

        // Sprawdzamy, czy dla roku o jeden mniejszego niż bieżący isValidYear zwraca false
        $this->assertFalse($result2);
    }
}
