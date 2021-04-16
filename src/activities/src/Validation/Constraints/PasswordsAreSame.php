<?php

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 */
class PasswordsAreSame extends Constraint
{
    public $message = 'Passwords are not the same';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
