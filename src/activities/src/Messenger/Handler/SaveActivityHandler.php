<?php

namespace App\Messenger\Handler;

use App\Entity\SavedActivity;
use App\Messenger\Message\SaveActivity;
use App\Repository\SavedActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SaveActivityHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, SavedActivityRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function __invoke(SaveActivity $message): void
    {
        $userActivities = $this->repository->findBy(['user' => $message->getUser()]);
        foreach ($userActivities as $userActivity) {
            if ($userActivity->getType()->getId() === $message->getActivity()->getType()->getId() &&
                strtolower($userActivity->getName()) === strtolower($message->getActivity()->getName())) {
                return;
            }
        }

        $this->entityManager->persist(SavedActivity::createFromActivity($message->getActivity()));
        $this->entityManager->flush();
    }
}
