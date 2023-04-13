<?php

namespace App\ServiceB\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDb;

#[MongoDb\Document]
class Payload
{
    #[MongoDb\Id]
    protected string $id;

    #[MongoDb\Field(type: 'string')]
    protected string $name;

    #[MongoDb\Field(type: 'string')]
    protected string $data;

    public function setName(string $name)
    {
        $this->name = $name;
        
        return $this;
    }

    public function setData(string $data)
    {
        $this->data = $data;
        
        return $this;
    }
    
    public function getId() { return $this->id; }

    public function getName() { return $this->name; }

    public function getData() { return $this->data; }
}