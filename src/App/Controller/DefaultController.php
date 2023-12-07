<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExchangeRateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response,};

/**
 * Class DefaultController
 */
class DefaultController extends AbstractController
{
    /**
     * @var ExchangeRateService
     */
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'exchange_rates/app-root.html.twig'
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function setupCheck(Request $request): Response
    {
        $responseContent = json_encode([
            'testParam' => $request->get('testParam')
                ? (int) $request->get('testParam')
                : null
        ]);
        return new Response(
            $responseContent,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
