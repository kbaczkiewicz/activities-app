<?php


namespace App\Controller\SavedActivity;


use App\Controller\BaseController;
use App\Entity\SavedActivity;
use App\Repository\SavedActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetSavedActivities extends AbstractController implements BaseController
{
    private $repository;

    public function __construct(SavedActivityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/api/savedActivity", name="get_saved_activities", methods={"get"})
     */
    public function process(Request $request): Response
    {
        return new JsonResponse(
            [
                'data' => array_map(
                    function (SavedActivity $activity) {
                        return \App\Response\SavedActivity::fromModel($activity);
                    },
                    $this->repository->findBy(['user' => $this->getUser()])
                ),
            ]
        );
    }
}
