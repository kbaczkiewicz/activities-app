<?php

namespace App\Messenger\Handler;

use App\Entity\IntervalStats;
use App\Messenger\Message\IntervalPostFinishStats;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class IntervalPostFinishStatsHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $activityRepository;

    public function __construct(EntityManagerInterface $entityManager, ActivityRepository $activityRepository)
    {
        $this->entityManager = $entityManager;
        $this->activityRepository = $activityRepository;
    }

    public function __invoke(IntervalPostFinishStats $message)
    {
        $interval = $message->getInterval();
        $stats = new IntervalStats();
        $stats->setInterval($interval);
        $stats->setCompletedActivities(...$this->activityRepository->getCompletedActivities($interval));
        $stats->setFailedActivities(...$this->activityRepository->getFailedActivities($interval));
        $this->entityManager->persist($stats);
    }
}
