<?php


namespace App\Controller\Interval;


use App\Controller\BaseController;
use App\Entity\Interval;
use App\Repository\IntervalRepository;
use App\Response\Interval as IntervalResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetIntervals extends AbstractController implements BaseController
{
    private $intervalRepository;

    public function __construct(IntervalRepository $intervalRepository)
    {
        $this->intervalRepository = $intervalRepository;
    }

    /**
     * @Route("/api/interval", name="get_intervals", methods={"get"})
     */
    public function process(Request $request): Response
    {
        return new JsonResponse(
            [
                "data" =>
                    array_map(
                        function (Interval $interval) {
                            return IntervalResponse::fromModel($interval);
                        },
                        $this->intervalRepository->findBy(['user' => $this->getUser()])
                    ),
            ]
        );
    }
}
