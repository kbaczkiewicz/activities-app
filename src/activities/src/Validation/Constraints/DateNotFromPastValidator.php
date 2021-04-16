<?php


namespace App\Validation\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateNotFromPastValidator extends ConstraintValidator
{
    /**
     * @param \DateTime $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($value < new \DateTime('now 00:00:00')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $value->format('Y-m-d H:i:s'))
                ->addViolation();
        }
    }
}
