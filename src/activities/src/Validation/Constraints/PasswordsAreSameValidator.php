<?php

namespace App\Validation\Constraints;

use App\Request\User\ChangePassword;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordsAreSameValidator extends ConstraintValidator
{
    /**
     * @param ChangePassword $value
     * @param PasswordsAreSame $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if ($value->getPassword() !== $value->getPasswordRepeat()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
