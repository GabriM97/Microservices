<?php

namespace App\ServiceB\Validator;

use Symfony\Component\Validator\Constraint;

class Payload extends Constraint
{
    public string $message = 'The payload is not valid because {{ reason }}.';
}