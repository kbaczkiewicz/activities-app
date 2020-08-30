<?php


namespace App\Controller\ActivityType;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\ActivityType;
use App\Repository\ActivityTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditType extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;
    private $entityManager;
    private $activityTypeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ActivityTypeRepository $activityTypeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->activityTypeRepository = $activityTypeRepository;
    }

    /**
     * @Route("/api/activityType/{id}", name="edit_activity_type", methods={"patch"})
     */
    public function process(Request $request): Response
    {
        $typeId = $request->get('id');
        if (null === $typeId) {
            return $this->createBadRequestResponse('You must provide type id');
        }

        $editType = \App\Request\ActivityType\EditType::fromArray(json_decode($request->getContent(), true));
        $type = $this->getType($typeId);
        if (null === $type) {
            return $this->createNotFoundResponse('Activity type not found');
        }

        if ($this->getUser()->getId() !== $type->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this resource');
        }

        $this->editActivityType($type, $editType);

        return new JsonResponse();
    }

    private function getType(string $id): ?ActivityType
    {
        return $this->activityTypeRepository->find($id);
    }

    private function editActivityType(?ActivityType $type, \App\Request\ActivityType\EditType $editType)
    {
        if (null !== $editType->getName()) {
            $type->setName($editType->getName());
        }

        if (null !== $editType->getDaysSpan()) {
            $type->setDaysSpan($editType->getDaysSpan());
        }

        $this->entityManager->persist($type);
        $this->entityManager->flush();
    }
}
