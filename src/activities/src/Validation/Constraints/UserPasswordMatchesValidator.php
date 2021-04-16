<?php

namespace App\Validation\Constraints;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserPasswordMatchesValidator extends ConstraintValidator
{
    private $tokenStorage;
    private $passwordEncoder;

    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->tokenStorage = $tokenStorage;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UserPasswordMatches $constraint
     */
    public function validate($password, Constraint $constraint)
    {
        if (null === $password || empty($password)) {
            return;
        }


        $user = $this->tokenStorage->getToken()->getUser();
        if (false === $this->passwordEncoder->isPasswordValid($user, $password)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
