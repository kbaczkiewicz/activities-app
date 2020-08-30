<?php


namespace App\Controller\Interval;


use App\Controller\BaseController;
use App\Entity\Interval;
use App\Enum\IntervalStatus;
use App\Request\Interval\CreateInterval as CreateIntervalRequest;
use App\Value\Identificator\IntervalId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateInterval extends AbstractController implements BaseController
{
    private $validator;
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/interval", name="create_interval", methods={"post"})
     */
    public function process(Request $request): Response
    {
        $createIntervalRequest = CreateIntervalRequest::fromArray(json_decode($request->getContent(), true) ?? []);
        $errors = $this->validator->validate($createIntervalRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $intervalId = $this->saveInterval($this->createModel($createIntervalRequest));

        return new JsonResponse(['data' => ['intervalId' => $intervalId->getId()]]);
    }

    private function createModel(CreateIntervalRequest $createIntervalRequest): Interval
    {
        $interval = new Interval();
        $interval->setName($createIntervalRequest->getName());
        $interval->setDateStart($createIntervalRequest->getDateStart());
        $interval->setDateEnd($createIntervalRequest->getDateEnd());
        $interval->setStatus(IntervalStatus::STATUS_NEW);
        $interval->setUser($this->getUser());

        return $interval;
    }

    private function saveInterval(Interval $interval): IntervalId
    {
        $this->entityManager->persist($interval);
        $this->entityManager->flush();

        return new IntervalId($interval->getId());
    }
}
