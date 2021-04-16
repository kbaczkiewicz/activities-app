<?php


namespace App\Controller\Activity;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\Activity;
use App\Enum\ActivityStatus;
use App\Messenger\Message\NewActivityIteration;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Request\Activity\EmptyEditActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Request\Activity\EditActivity as EditActivityRequest;

class EditActivity extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $activityRepository;
    private $validator;
    private $activityTypeRepository;
    private $entityManager;
    private $messageBus;

    public function __construct(
        ActivityRepository $activityRepository,
        ActivityTypeRepository $activityTypeRepository,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus
    ) {
        $this->activityRepository = $activityRepository;
        $this->validator = $validator;
        $this->activityTypeRepository = $activityTypeRepository;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/activity/{id}", name="edit_activity", methods={"patch"})
     */
    public function process(Request $request): Response
    {
        try {
            $activity = $this->activityRepository->find($request->get('id'));
            if (null === $activity) {
                return $this->createNotFoundResponse('Activity does not exist');
            }

            if ($this->getUser()->getId() !== $activity->getUser()->getId()) {
                $this->createForbiddenResponse('You are not permitted to see this action');
            }

            $editActivityRequest = EditActivityRequest::fromArray(json_decode($request->getContent(), true));
            if (ActivityStatus::STATUS_CREATED === $activity->getStatus()
                && $editActivityRequest instanceof EmptyEditActivity
            ) {
                $this->markActivityAsCompleted($activity);
            } else {
                $errors = $this->validator->validate($editActivityRequest);
                if ($errors->count() > 0) {
                    return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
                }

                $this->saveActivity($activity, $editActivityRequest);
            }

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['reason' => 'Activity type does not exist', Response::HTTP_NOT_FOUND]);
        }
    }

    private function saveActivity(Activity $activity, EditActivityRequest $editActivityRequest): void
    {
        if ($editActivityRequest->getName()) {
            $activity->setName($editActivityRequest->getName());
        }

        if ($editActivityRequest->getTypeId()) {
            $activityType = $this->activityTypeRepository->find($editActivityRequest->getTypeId()->getId());
            if (null === $activityType) {
                throw new \InvalidArgumentException('Activity id not found');
            }
            $activity->setType($activityType);
        }

        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }

    private function markActivityAsCompleted(Activity $activity): void
    {
        $activity->setStatus(ActivityStatus::STATUS_COMPLETED);
        $this->entityManager->persist($activity);
        $this->messageBus->dispatch(new NewActivityIteration($activity));
        $this->entityManager->flush();
    }
}
