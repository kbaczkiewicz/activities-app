<?php

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AtLeastOneNotBlankValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $error = true;
            $reflection = new \ReflectionClass($value);
            foreach ($reflection->getProperties() as $property) {
                if (!empty($value->{'get'.ucfirst($property->getName())}())) {
                    $error = false;
                    break;
                }
            }

            if ($error) {
                $this->context
                    ->buildViolation(sprintf('All fields of class %s are empty', get_class($value)))
                    ->addViolation();
            }
        } catch (\ReflectionException $e) {
            $this->context
                ->buildViolation(sprintf('Could not get access to all properties of class %s', get_class($value)))
                ->addViolation();
        }
    }
}
