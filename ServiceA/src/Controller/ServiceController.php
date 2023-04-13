<?php

namespace App\ServiceA\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Throwable;

class ServiceController extends AbstractController
{
    private array $payloads = [];

    private const SERVICE_B_ENDPOINT = 'http://service-b-api/process';

    public function __construct(private ClientInterface $httpClient, private LoggerInterface $logger)
    {
        $this->payloads = [
            'X' => [
                'name' => 'X',
                'data' => 'my_data',
            ],
            'Y' => [
                'name' => 'Z', // 'Z' will make the validation fail in service-b
                'data' => 'my_data', // remove the 'data' field to get another validation error
            ],
        ];
    }

    /**
     * Run the service with the passed {payload} which will need to match the route requirement.
     *
     * @param  string $payload  the payload to use.
     * @return JsonResponse
     */
    #[Route("/service/run/{payload}", name: "service_run", methods: 'GET', requirements: ['payload' => '[X|Y]{1}'])]    
    public function run(string $payload) : JsonResponse 
    {
        try {
            $this->logger->info('Sending payload "' . $payload . '" to "' . self::SERVICE_B_ENDPOINT .'"');

            // fire http request to ServiceB using one of the payloads.
            $response = $this->httpClient->request('POST', self::SERVICE_B_ENDPOINT, ['json' => $this->payloads[$payload]]);
        } catch (RequestException $e) {
            $this->logger->error('Error (' . $e->getCode() . ') while sending payload: ' . $e->getMessage());
            
            $response = $e->getResponse();
        }

        return $this->json(json_decode($response->getBody()), $response->getStatusCode());
    }

    /**
     * Run the service with all the payloads.
     *
     * @return JsonResponse
     */
    #[Route("/service/run", name: "service_run_both", methods: 'GET')]
    public function runBoth() : JsonResponse 
    {
        $messages = [];
        foreach ($this->payloads as $key => $payload) {
            try {
                $this->logger->info('Sending payload "' . $key . '" to "' . self::SERVICE_B_ENDPOINT .'"');

                // fire http request to ServiceB using the current payload.
                $response = $this->httpClient->request('POST', self::SERVICE_B_ENDPOINT, ['json' => $payload]);
            } catch (RequestException $e) {
                $this->logger->error('Error (' . $e->getCode() . ') while sending payload: ' . $e->getMessage());
                
                $response = $e->getResponse();
            }

            $responseMessage = json_decode($response->getBody(), true);
            $messages[$response->getStatusCode()][] = $responseMessage['data'] ?? $responseMessage;
        }

        $maxStatusCode = array_key_last($messages);

        return $this->json(['data' => (object) $messages], $maxStatusCode);
    }
}