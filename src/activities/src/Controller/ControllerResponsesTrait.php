<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ControllerResponsesTrait
{
    private function createNotFoundResponse(string $message): JsonResponse
    {
        return $this->createResponse(['reason' => $message], Response::HTTP_NOT_FOUND);
    }

    private function createUnauthorizedResponse(string $message): JsonResponse
    {
        return $this->createResponse(['reason' => $message], Response::HTTP_UNAUTHORIZED);

    }

    private function createForbiddenResponse(string $message): JsonResponse
    {
        return $this->createResponse(['reason' => $message], Response::HTTP_FORBIDDEN);

    }

    private function createBadRequestResponse(string $message): JsonResponse
    {
        return $this->createResponse(['reason' => $message], Response::HTTP_BAD_REQUEST);
    }

    private function createConflictResponse(string $message): JsonResponse
    {
        return $this->createResponse(['reason' => $message], Response::HTTP_CONFLICT);
    }

    private function createResponse(array $data, int $code)
    {
        return new JsonResponse([$data, $code]);
    }

}
