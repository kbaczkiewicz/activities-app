<?php

namespace App\Controller\Activity;

use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Repository\IntervalRepository;
use App\Response\Activity as ActivityResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetUniqueActivities extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $activityRepository;
    private $intervalRepository;

    public function __construct(ActivityRepository $activityRepository, IntervalRepository $intervalRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->intervalRepository = $intervalRepository;
    }

    /**
     * @Route("/api/activity/{intervalId}/unique", priority="10", name="get_unique_activities", methods={"get"})
     */
    public function process(Request $request): Response
    {
        $interval = $this->intervalRepository->find($request->get('intervalId', null));
        if (null === $interval) {
            return $this->createBadRequestResponse('Interval not found');
        }

        return new JsonResponse(
            array_map(
                function (Activity $activity) {
                    return ActivityResponse::fromModel($activity);
                },
                $this->activityRepository->findUniqueByInterval($interval)
            )
        );

    }
}
