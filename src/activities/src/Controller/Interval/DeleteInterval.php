<?php

namespace App\Controller\Interval;

use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Repository\IntervalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteInterval extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, IntervalRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @Route("/api/interval/{id}", name="delete_interval", methods={"delete"})
     */
    public function process(Request $request): Response
    {
        $intervalId = $request->get('id');
        if (null === $intervalId) {
            return $this->createBadRequestResponse('You must provide activity ID');
        }

        $interval = $this->repository->find($intervalId);
        if (null === $interval) {
            return $this->createNotFoundResponse('Activity not found');
        }

        if ($interval->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this access');
        }

        $this->entityManager->remove($interval);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
