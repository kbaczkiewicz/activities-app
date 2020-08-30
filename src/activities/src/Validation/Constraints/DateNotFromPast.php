<?php


namespace App\Validation\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateNotFromPast extends Constraint
{
    public $message = "Date {{date}} can not be from the past";
}
