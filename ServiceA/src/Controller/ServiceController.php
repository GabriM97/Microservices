<?php

namespace App\ServiceA\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

class ServiceController extends AbstractController
{
    /**
     * @var array $payloads
     */
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
                'name' => 'Z', // 'Z' will make the validation fail in ServiceB
                'data' => 'my_data', // remove the 'data' field to get another validation error
            ],
        ];
    }

    /**
     * Run the service with the passed {payload} which will need to match the route requirement 'X' or 'Y'.
     *
     * @param  string $payload  the payload to use.
     * 
     * @return JsonResponse
     */
    #[Route("/service/run/{payload}", name: "service_run", methods: 'GET', requirements: ['payload' => '[X|Y]{1}'])]    
    public function run(string $payload) : JsonResponse 
    {
        $response = $this->sendJsonPayload($this->payloads[$payload], self::SERVICE_B_ENDPOINT);

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
        foreach ($this->payloads as $payload) {
            // send payload to ServiceB
            $response = $this->sendJsonPayload($payload, self::SERVICE_B_ENDPOINT);
            
            // json decode response message and store it for later
            $responseMessage = json_decode($response->getBody(), true);
            $messages[$response->getStatusCode()][] = $responseMessage['data'] ?? $responseMessage;
        }

        // get the higher status code set (the array last key) and return it in the response.
        $maxStatusCode = array_key_last($messages);

        return $this->json(['data' => (object) $messages], $maxStatusCode);
    }

        
    /**
     * Send the passed payload in a json http request to the given endpoint.
     *
     * @param  array $payload the payload to send
     * @param  string $endpoint the full url to use for the request 
     * @param  string $method the request method, 'POST' by default.
     * 
     * @return Response
     */
    private function sendJsonPayload(array $payload, string $endpoint, string $method = 'POST'): Response
    {
        try {
            $this->logger->info('Sending payload "' . $payload['name'] . '" to "' . $endpoint .'"');

            // send json http request to ServiceB using the payload.
            $response = $this->httpClient->request(
                $method, $endpoint, 
                ['json' => $payload],
                ['headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]]
            );
        } catch (RequestException $e) {
            $this->logger->error('Error (' . $e->getCode() . ') while sending payload: ' . $e->getMessage());
            // get the original response from the exception
            $response = $e->getResponse();
        }

        return $response;
    }
}