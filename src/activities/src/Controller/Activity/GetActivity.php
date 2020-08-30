<?php


namespace App\Controller\Activity;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Repository\ActivityRepository;
use App\Repository\IntervalRepository;
use App\Response\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetActivity extends AbstractController implements BaseController
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
     * @Route("/api/activity/{intervalId}/{id}", name="get_activity", methods={"get"})
     */
    public function process(Request $request): Response
    {
        $intervalId = $request->get('intervalId');
        $id = $request->get('id');
        if (null === $intervalId || null === $id) {
            return $this->createBadRequestResponse('You should provide an activity id and an interval id');
        }

        $activity = $this->getActivity($id, $intervalId);
        if (null === $activity) {
            return $this->createNotFoundResponse('Activity not found');
        }

        return new JsonResponse(Activity::fromModel($activity));
    }

    private function getActivity(string $activityId, string $intervalId)
    {
        $interval = $this->intervalRepository->find($intervalId);
        if (null === $interval) {
            return null;
        }

        return $this->repository->findOneBy(['interval' => $interval, 'id' => $activityId, 'user' => $this->getUser()]);
    }
}
