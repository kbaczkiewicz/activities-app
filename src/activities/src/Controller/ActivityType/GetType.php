<?php


namespace App\Controller\ActivityType;


use App\Controller\BaseController;
use App\Controller\ControllerResponsesTrait;
use App\Entity\ActivityType;
use App\Repository\ActivityTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetType extends AbstractController implements BaseController
{
    use ControllerResponsesTrait;

    private $repository;

    public function __construct(ActivityTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/api/activityType/{id}", name="get_activity_type", methods={"get"})
     */
    public function process(Request $request): Response
    {
        $typeId = $request->get('id');
        if (null === $typeId) {
            return $this->createBadRequestResponse('You must provide type id');
        }

        $type = $this->getType($typeId);
        if ($this->getUser()->getId() !== $type->getUser()->getId()) {
            return $this->createForbiddenResponse('You are not permitted to see this resource');
        }

        return new JsonResponse(['data' => \App\Response\ActivityType::fromModel($type)]);
    }

    private function getType($typeId): ?ActivityType
    {
        return $this->repository->find($typeId);
    }
}
