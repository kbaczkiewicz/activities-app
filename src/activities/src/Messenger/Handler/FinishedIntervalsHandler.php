<?php


namespace App\Messenger\Handler;


use App\Enum\IntervalStatus;
use App\Messenger\Message\FinishedIntervals;
use App\Messenger\Message\IntervalPostFinishStats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FinishedIntervalsHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function __invoke(FinishedIntervals $message)
    {
        foreach ($message->getIntervals() as $interval) {
            $interval->setStatus(IntervalStatus::STATUS_ENDED);
            $this->entityManager->persist($interval);
            $this->messageBus->dispatch(new IntervalPostFinishStats($interval));
        }

        $this->entityManager->flush();
    }
}
