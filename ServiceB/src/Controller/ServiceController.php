<?php

namespace App\ServiceB\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{

    #[Route("/process", name: "service_process")]
    public function process() : JsonResponse 
    {
        return $this->json(['hello world from Service B']);
    }
}