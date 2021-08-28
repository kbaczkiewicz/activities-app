<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\User;
use App\Enum\IntervalStatus;
use App\Repository\ActivityRepository;
use App\Repository\IntervalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetProfileData extends AbstractController implements BaseController
{
    private $activityRepository;
    private $intervalRepository;

    public function __construct(ActivityRepository $activityRepository, IntervalRepository $intervalRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->intervalRepository = $intervalRepository;
    }


    /**
     * @Route("/api/account", name="get_profile_data", methods={"GET"})
     */
    public function process(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse([
           'data' => [
               'email' => $user->getUsername(),
               'activeIntervals' => count($this->intervalRepository->findBy(
                   ['user' => $user, 'status' => IntervalStatus::STATUS_STARTED]
               )),
               'endedIntervals' => count($this->intervalRepository->findBy(
                   ['user' => $user, 'status' => IntervalStatus::STATUS_ENDED]
               )),
               'intervalsToStart' => count($this->intervalRepository->findBy(
                   ['user' => $user, 'status' => [
                       IntervalStatus::STATUS_DRAFT,
                       IntervalStatus::STATUS_NEW,
                       IntervalStatus::STATUS_SAVED]
                   ]
               )),
               'completedActivities' => count($this->activityRepository->findCompletedActivitiesForUser($user)),
               'failedActivities' => count($this->activityRepository->findFailedActivitiesForUser($user))
           ]
        ]);
    }
}
