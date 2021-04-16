<?php


namespace App\Controller\Activity;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Enum\ActivityStatus;
use App\Repository\ActivityTypeRepository;
use App\Request\Activity\CreateActivity as CreateActivityRequest;
use App\Value\Identificator\ActivityId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateActivity extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $entityManager;
    private $activityTypeRepository;
    private $validator;
    private $userProvider;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivityTypeRepository $activityTypeRepository,
        ValidatorInterface $validator,
        UserProviderInterface $userProvider
    ) {
        $this->entityManager = $entityManager;
        $this->activityTypeRepository = $activityTypeRepository;
        $this->validator = $validator;
        $this->userProvider = $userProvider;
    }

    /**
     * @Route("/api/activity", name="create_activity", methods={"post"})
     */
    public function process(Request $request): Response
    {
        $createActivityRequest = CreateActivityRequest::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($createActivityRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $activityType = $this->activityTypeRepository->find($createActivityRequest->getTypeId()->getId());
        if (null === $activityType) {
            return $this->createNotFoundResponse('Activity type does not exist');
        }

        $activityId = $this->createActivity($createActivityRequest, $activityType, $this->getUser());

        return new JsonResponse(['data' => ['id' => $activityId->getId()]], Response::HTTP_CREATED);
    }

    private function createActivity(
        CreateActivityRequest $createActivityRequest,
        ActivityType $activityType,
        UserInterface $user
    ): ActivityId {
        $activity = new Activity();
        $activity->setName($createActivityRequest->getName());
        $activity->setStatus(ActivityStatus::STATUS_CREATED);
        $activity->setType($activityType);
        $activity->setUser($user);


        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return new ActivityId($activity->getId());
    }
}
