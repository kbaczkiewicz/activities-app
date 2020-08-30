<?php


namespace App\Controller\SavedActivity;


use App\Controller\BaseController;
use App\Entity\SavedActivity;
use App\Repository\ActivityTypeRepository;
use App\Request\SavedActivity\CreateSavedActivity as CreateSavedActivityRequest;
use App\Value\Identificator\SavedActivityId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateSavedActivity extends AbstractController implements BaseController
{
    private $validator;
    private $entityManager;
    private $activityTypeRepository;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        ActivityTypeRepository $activityTypeRepository
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->activityTypeRepository = $activityTypeRepository;
    }

    /**
     * @Route("/api/savedActivity", name="create_saved_activity", methods={"post"})
     */
    public function process(Request $request): Response
    {
        $createSavedActivityRequest = CreateSavedActivityRequest::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($createSavedActivityRequest);
        if ($errors->count() > 0) {
            return new JsonResponse(['errors' => (string)$errors]);
        }

        $savedActivityId = $this->createSavedActivity($createSavedActivityRequest);

        return new JsonResponse(['data' => ['id' => $savedActivityId->getId()]]);
    }

    private function createSavedActivity(CreateSavedActivityRequest $createSavedActivityRequest): SavedActivityId
    {
        $type = $this->activityTypeRepository->find($createSavedActivityRequest->getTypeId());
        $savedActivity = new SavedActivity();
        $savedActivity->setName($createSavedActivityRequest->getName());
        $savedActivity->setType($type);
        $savedActivity->setUser($this->getUser());
        $this->entityManager->persist($savedActivity);
        $this->entityManager->flush();

        return new SavedActivityId($savedActivity->getId());
    }
}
