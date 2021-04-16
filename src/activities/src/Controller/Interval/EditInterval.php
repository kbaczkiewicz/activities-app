<?php


namespace App\Controller\Interval;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\Interval;
use App\Enum\IntervalStatus;
use App\Repository\IntervalRepository;
use App\Request\Interval\EditInterval as EditIntervalRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditInterval extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $repository;
    private $entityManager;
    private $validator;

    public function __construct(
        IntervalRepository $repository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/interval/{id}", name="edit_interval", methods={"patch"})
     */
    public function process(Request $request): Response
    {
        $interval = $this->getInterval($request->get('id'));
        if (null === $interval) {
            return $this->createNotFoundResponse('Interval not found');
        }

        if ($interval->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this resource');
        }

        $editIntervalRequest = EditIntervalRequest::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($editIntervalRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        if (false === in_array($interval->getStatus(), IntervalStatus::getEditionSafeStatuses())) {
            return $this->createConflictResponse('Interval can no longer be modified');
        }

        $this->saveInterval($interval, $editIntervalRequest);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function getInterval(string $intervalId): ?Interval
    {
        return $this->repository->find($intervalId);
    }

    private function saveInterval(Interval $interval, EditIntervalRequest $request): void
    {
        if ($request->getName()) {
            $interval->setName($request->getName());
        }

        if ($request->getDateStart()) {
            $interval->setDateStart($request->getDateStart());
        }

        if ($request->getDateEnd()) {
            $interval->setDateEnd($request->getDateEnd());
        }

        $this->entityManager->persist($interval);
        $this->entityManager->flush();
    }
}
