<?php


namespace App\Controller\Interval;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Repository\IntervalRepository;
use App\Response\Interval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetInterval extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $repository;

    public function __construct(IntervalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/api/interval/{id}", name="get_interval", methods={"get"})
     */
    public function process(Request $request): Response
    {
        $interval = $this->repository->find($request->get('id'));
        if (null === $interval) {
            return $this->createNotFoundResponse('Interval not found');
        }

        if ($interval->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this resource');
        }

        return new JsonResponse(['data' => Interval::fromModel($interval)]);
    }
}
