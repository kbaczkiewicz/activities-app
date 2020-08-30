<?php


namespace App\Messenger\Message;


use App\Enum\IntervalStatus;
use App\Messenger\Handler\FinishedIntervals;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FinishedIntervalsHandler implements MessageHandlerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(FinishedIntervals $message)
    {
        foreach ($message->getIntervals() as $interval) {
            $interval->setStatus(IntervalStatus::STATUS_ENDED);
            $this->entityManager->persist($interval);
        }

        $this->entityManager->flush();
        /** @todo implement push message */
    }
}
