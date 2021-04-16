<?php

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 */
class UserPasswordMatches extends Constraint
{
    public $message = 'Password does not match user password';
}
