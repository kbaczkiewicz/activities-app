<?php

namespace App\Messenger\Handler;

use App\Entity\Interval;
use App\Enum\IntervalStatus;
use App\Messenger\Message\CreatedIntervalsToStart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatedIntervalsToStartHandler implements MessageHandlerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreatedIntervalsToStart $message): void
    {
        foreach ($message->getIntervals() as $interval) {
            $interval->setStatus(IntervalStatus::STATUS_STARTED);
            $this->entityManager->persist($interval);
        }
    }
}
