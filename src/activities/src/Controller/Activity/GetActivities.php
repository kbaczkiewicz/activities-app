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

/** @todo implement filters */
class GetActivities extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $repository;
    private $intervalRepository;

    public function __construct(ActivityRepository $repository, IntervalRepository $intervalRepository)
    {
        $this->repository = $repository;
        $this->intervalRepository = $intervalRepository;
    }

    /**
     * @Route("/api/activity", name="get_all_activities", methods={"get"})
     * @Route("/api/activity/{intervalId}", name="get_activities", methods={"get"})
     */
    public function process(Request $request): Response
    {
        $intervalId = $request->get('intervalId');
        return new JsonResponse(
            array_map(
                function (Activity $activity) {
                    return ActivityResponse::fromModel($activity);
                },
                $this->getActivities($intervalId)
            )
        );
    }

    private function getActivities(?string $intervalId): array
    {
        $criteria = ['user' => $this->getUser()];
        if (null !== $intervalId) {
            $interval = $this->intervalRepository->find($intervalId);
            if (null === $interval) {
                return [];
            }

            $criteria['interval'] = $interval;
        }


        return $this->repository->findBy($criteria);
    }
}
