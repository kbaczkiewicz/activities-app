<?php


namespace App\Messenger\Handler;


use App\Enum\ActivityStatus;
use App\Messenger\Message\FailedActivities;
use App\Messenger\Message\NewActivityIteration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FailedActivitiesHandler implements MessageHandlerInterface
{

    private $entityManager;
    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function __invoke(FailedActivities $message)
    {
        foreach ($message->getActivities() as $activity) {
            $activity->setStatus(ActivityStatus::STATUS_FAILED);
            $this->entityManager->persist($activity);
            $this->messageBus->dispatch(new NewActivityIteration($activity));
        }

        $this->entityManager->flush();
        /** @todo implement push message */
    }
}
