<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChangePassword extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $validator;
    private $entityManager;
    private $passwordEncoder;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/api/account", name="change_user_password", methods={"patch"})
     */
    public function process(Request $request): Response
    {
        $user = $this->getUser();
        $changePasswordRequest = \App\Request\User\ChangePassword::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($changePasswordRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $changePasswordRequest->getPasswordRepeat()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
