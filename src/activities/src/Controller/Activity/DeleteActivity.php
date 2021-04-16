<?php

namespace App\Controller\Activity;

use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteActivity extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $repository;
    private $entityManager;

    public function __construct(ActivityRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function process(Request $request): Response
    {
        $activityId = $request->get('id');
        if (null === $activityId) {
            return $this->createBadRequestResponse('You must provide activity ID');
        }

        $activity = $this->repository->find($activityId);
        if (null === $activity) {
            return $this->createNotFoundResponse('Activity not found');
        }

        if ($activity->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this action');
        }

        $this->entityManager->remove($activity);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
