<?php

namespace App\ServiceB\Controller;

use App\ServiceB\Validator\Payload as PayloadConstraint;
use App\ServiceB\BusinessLogic\MyBusinessLogic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ServiceController extends AbstractController
{
    public function __construct(protected MyBusinessLogic $businessLogic)
    {
        
    }

    #[Route("/process", name: "service_process", methods: 'POST')]
    public function process(Request $request, ValidatorInterface $validator) : JsonResponse 
    {
        // get the raw json payload and return to array
        $payload = $request->toArray();

        // validate the given payload using the PayloadConstraint
        $errors = $validator->validate($payload, new PayloadConstraint());
        if (count($errors)) {
            return $this->json(['error_message' => $errors->get(0)->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // do stuff with the payload
        $storedPayloadId = $this->businessLogic->doStuff($payload);

        return $this->json(['payload_id' => $storedPayloadId]);
    }
}