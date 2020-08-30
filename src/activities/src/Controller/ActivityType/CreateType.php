<?php


namespace App\Controller\ActivityType;

use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\ActivityType;
use App\Value\Identificator\ActivityTypeId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateType extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $entityManager;
    private $validator;


    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/activityType", name="create_activity_type", methods={"post"})
     */
    public function process(Request $request): Response
    {
        $createTypeRequest = \App\Request\ActivityType\CreateType::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($createTypeRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['data' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $activityTypeId = $this->createType($createTypeRequest);

        return new JsonResponse(['data' => $activityTypeId->getId()]);
    }

    private function createType(\App\Request\ActivityType\CreateType $createTypeRequest): ActivityTypeId
    {
        $type = new ActivityType();
        $type->setUser($this->getUser());
        $type->setName($createTypeRequest->getName());
        $type->setDaysSpan($createTypeRequest->getDaysSpan());
        $this->entityManager->persist($type);
        $this->entityManager->flush();

        return new ActivityTypeId($type->getId());
    }
}
