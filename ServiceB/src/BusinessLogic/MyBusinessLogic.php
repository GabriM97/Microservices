<?php

namespace App\ServiceB\BusinessLogic;

use App\ServiceB\Document\Payload;
use Doctrine\ODM\MongoDB\DocumentManager;

class MyBusinessLogic {
    public function __construct(private DocumentManager $dm)
    {
        
    }
        
    /**
     * Function that performs stuff
     *
     * @param  array $data
     * 
     * @return string
     */
    public function doStuff(array $data): string
    {
        // apply business logic...

        return $this->storeData($data);
    }
    
    /**
     * Create a new Payload from $data and store to DB.
     *
     * @param  array $data
     * @return string
     */
    private function storeData(array $data): string
    {
        // create Payload Document
        $payload = (new Payload())
            ->setName($data['name'])
            ->setData($data['data']);

        // store Payload to DB
        $this->dm->persist($payload);
        $this->dm->flush();

        return $payload->getId();
    }
}