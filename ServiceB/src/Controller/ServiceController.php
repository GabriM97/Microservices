<?php

namespace App\ServiceB\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{

    #[Route("/get", name: "service_run")]
    public function run() : JsonResponse 
    {
        return $this->json(['hello world from Service B']);
    }
}