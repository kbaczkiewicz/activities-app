<?php

namespace App\Controller\IntervalStats;

use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Repository\IntervalRepository;
use App\Service\IntervalStats\StatsCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetForInterval extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $intervalRepository;
    private $statsCalculator;

    public function __construct(IntervalRepository $intervalRepository, StatsCalculator $statsCalculator)
    {
        $this->intervalRepository = $intervalRepository;
        $this->statsCalculator = $statsCalculator;
    }

    /**
     * @Route("/api/intervalStats/{id}", name="get_interval_stats", methods={"GET"})
     */
    public function process(Request $request): Response
    {
        $interval = $this->intervalRepository->find($request->get('id'));
        if (null === $interval) {
            return $this->createNotFoundResponse('Interval not found');
        }

        if ($interval->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this resource');
        }

        return new JsonResponse(['data' => $this->statsCalculator->calculate($interval)]);
    }
}
