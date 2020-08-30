<?php


namespace App\Controller\ActivityType;

use App\Controller\BaseController;
use App\Entity\ActivityType;
use App\Repository\ActivityTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetTypes extends AbstractController implements BaseController
{

    private $activityTypeRepository;

    public function __construct(ActivityTypeRepository $activityTypeRepository)
    {
        $this->activityTypeRepository = $activityTypeRepository;
    }

    /**
     * @Route("/api/activityType", name="get_activity_types", methods={"get"})
     */
    public function process(Request $request): Response
    {
        $user = $this->getUser();

        return new JsonResponse(
            [
                'data' =>
                    array_map(
                        function (ActivityType $type) {
                            return \App\Response\ActivityType::fromModel($type);
                        },
                        $this->activityTypeRepository->findBy(['user' => $user])
                    ),
            ]
        );
    }
}
