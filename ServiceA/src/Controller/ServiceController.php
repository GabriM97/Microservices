<?php

namespace App\ServiceA\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client as GuzzleClient;
use Throwable;

class ServiceController extends AbstractController
{
    #[Route("/service/run", name: "service_run")]
    public function run() : JsonResponse 
    {
        $client = new GuzzleClient();
        try {
            $response = $client->request('GET', 'http://service-b-api/get');
        } catch (Throwable $e) {
            dd($e);
        }

        return $this->json([$response->getBody()->getContents(), $response->getStatusCode()]);
    }
}