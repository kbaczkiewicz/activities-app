<?php


namespace App\Controller\User;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\User;
use App\Value\Identificator\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Register implements BaseController
{
    use ControllerResponsesTrait;

    private $entityManager;
    private $validator;
    private $encoder;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/api/user", name="register_user", methods={"post"})
     */
    public function process(Request $request): Response
    {
        $registerRequest = \App\Request\User\Register::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($registerRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $userId = $this->saveUser($registerRequest);

        return new JsonResponse(['data' => ['id' => $userId->getId()]]);
    }

    private function saveUser(\App\Request\User\Register $registerRequest): UserId
    {
        $user = new User();
        $user->setEmail($registerRequest->getEmail());
        $user->setPassword($this->encoder->encodePassword($user, $registerRequest->getPassword()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new UserId($user->getId());
    }
}
