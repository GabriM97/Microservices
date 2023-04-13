<?php

namespace App\ServiceB\Exception;

class InvalidPayloadException extends \Exception
{
    /** 
     * @var array $errorMessages 
     **/
    protected array $errorMessages;

    public function __construct(string $message, int $code, protected iterable $errors)
    {
        parent::__construct($message, $code);
    }
    
    /**
     * Returns all the error messages
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        if (!isset($this->errorMessages)) {
            $this->errorMessages = $this->mapErrors($this->errors);
        }

        return $this->errorMessages;
    }
    
    /**
     * Maps the error message from a given iterable set of errors
     *
     * @param  iterable $errors
     * 
     * @return array
     */
    private function mapErrors(iterable $errors): array 
    {
        foreach ($errors as $e) {
            $errorMessages[] = $e->getMessage();
        }

        return $errorMessages;
    }
}