<?php

namespace App\Messenger\Handler;

use App\Enum\ActivityStatus;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Messenger\Message\CreatedActivitiesToStart;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatedActivitiesToStartHandler implements MessageHandlerInterface
{
    private $activityRepository;
    private $entityManager;

    public function __construct(ActivityRepository $activityRepository, EntityManagerInterface $entityManager)
    {
        $this->activityRepository = $activityRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreatedActivitiesToStart $message): void
    {
        foreach ($message->getActivities() as $activity) {
            $activity->setStatus(ActivityStatus::STATUS_PENDING);
            $this->entityManager->persist($activity);
        }
    }
}
