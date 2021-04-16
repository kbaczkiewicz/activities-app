<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetProfileData extends AbstractController implements BaseController
{
    /**
     * @Route("/api/account", name="get_profile_data", methods={"GET"})
     */
    public function process(Request $request): Response
    {
        $user = $this->getUser();

        return new JsonResponse([
           'data' => [
               'email' => $user->getUsername()
           ]
        ]);
    }
}
