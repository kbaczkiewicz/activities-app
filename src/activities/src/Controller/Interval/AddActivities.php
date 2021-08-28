<?php


namespace App\Controller\Interval;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\Interval;
use App\Enum\IntervalStatus;
use App\Messenger\Message\SaveActivity;
use App\Repository\ActivityRepository;
use App\Repository\IntervalRepository;
use App\Request\Interval\AddActivities as AddActivitiesRequest;
use App\Value\Identificator\ActivityId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddActivities extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $validator;
    private $intervalRepository;
    private $activityRepository;
    private $entityManager;
    private $messageBus;

    public function __construct(
        ValidatorInterface $validator,
        IntervalRepository $intervalRepository,
        ActivityRepository $activityRepository,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus
    ) {
        $this->validator = $validator;
        $this->intervalRepository = $intervalRepository;
        $this->activityRepository = $activityRepository;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/interval/{id}/activities", name="add_activities", methods={"patch"})
     */
    public function process(Request $request): Response
    {
        $intervalId = $request->get('id');
        $addActivitiesRequest = AddActivitiesRequest::fromArray(
            json_decode($request->getContent(), true) ?? []
        );

        $errors = $this->validator->validate($addActivitiesRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $interval = $this->getInterval($intervalId);
        if (null === $interval) {
            return $this->createNotFoundResponse('Interval not found');
        }

        if ($this->getUser()->getId() !== $interval->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this resource');
        }

        if (false === in_array($interval->getStatus(), IntervalStatus::getEditionSafeStatuses())) {
            return new JsonResponse(['reason' => 'Interval can no longer be modified'], Response::HTTP_CONFLICT);
        }

        $this->addActivities($interval, $addActivitiesRequest);

        return new JsonResponse();
    }

    private function getInterval(string $id): ?Interval
    {
        return $this->intervalRepository->find($id);
    }

    private function getActivities(ActivityId ...$ids): array
    {
        $idsValues = array_map(
            function (ActivityId $id) {
                return $id->getId();
            },
            $ids
        );

        return $this->activityRepository->findBy(['id' => $idsValues, 'user' => $this->getUser()]);
    }

    private function addActivities(?Interval $interval, AddActivitiesRequest $addActivitiesRequest)
    {
        $activities = $this->getActivities(...$addActivitiesRequest->getActivitiesIds());
        $interval->addActivities(...$activities);
        $this->entityManager->persist($interval);
        $this->entityManager->flush();
        foreach ($activities as $activity) {
            $this->messageBus->dispatch(new SaveActivity($interval->getUser(), $activity));
        }
    }
}
