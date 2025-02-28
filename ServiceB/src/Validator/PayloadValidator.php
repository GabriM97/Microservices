<?php

namespace App\ServiceB\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\ServiceB\Validator\Payload as PayloadConstraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PayloadValidator extends ConstraintValidator
{
    /**
     * @var array $validPayloadNames
     */
    private array $validPayloadNames = ['X', 'Y'];

    /**
     * @var PayloadConstraint $constraint constraint used to validate the payload
     */
    private PayloadConstraint $constraint;

    /**
     * @param array $payload
     * @param PayloadConstraint $constraint
     * 
     * @return void
     */
    public function validate($payload, Constraint $constraint): void
    {
        if (!$constraint instanceof PayloadConstraint) {
            throw new UnexpectedTypeException($constraint, PayloadConstraint::class);
        }

        $this->constraint = $constraint;
        
        $this->checkViolation(!is_array($payload), 'the payload is not an array');
        $this->checkViolation(empty($payload['name']), 'the property "name" is missing');
        $this->checkViolation(empty($payload['data']), 'the property "data" is missing');
        $this->checkViolation(!in_array($payload['name'] ?? '', $this->validPayloadNames), 'the payload "name" is not valid');
    }
    
    /**
     * If the passed condition is violated (is true) a new violation will be added to the validatior context
     *
     * @param  bool $violatingCondition the condition to check
     * @param  string $reason a string saying what's the problem with the condition 
     * 
     * @return void
     */
    private function checkViolation(bool $violatingCondition, string $reason) {
        if (!$violatingCondition) {
            return;
        }

        // add violation to context
        $this->context->buildViolation($this->constraint->message)
            ->setParameter('{{ reason }}', $reason)
            ->addViolation();
    }
}