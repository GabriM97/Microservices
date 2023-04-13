<?php

namespace App\ServiceB\BusinessLogic;

use App\ServiceB\Document\Payload;
use Doctrine\ODM\MongoDB\DocumentManager;

class MyBusinessLogic {
    public function __construct(private DocumentManager $dm)
    {
        
    }
    
    /** */
    public function doStuff(array $payload): int
    {
        // apply business logic...

        return $this->storePayload($payload);
    }

    private function storePayload(array $payload): int
    {
        $payloadDocument = new Payload();
        $payloadDocument->setName($payload['name']);
        $payloadDocument->setData($payload['data']);

        $this->dm->persist($payloadDocument);
        $this->dm->flush();

        return $payloadDocument->getId();
    }
}