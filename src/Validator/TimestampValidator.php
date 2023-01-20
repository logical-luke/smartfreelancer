<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TimestampValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var Timestamp $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation('Is not a valid integer.');
        }

        if (!$this->isValidTimestamp($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation('Is not a valid timestamp.');
        }
    }

    public function isValidTimestamp(int $timestamp): bool
    {
        return ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }
}
