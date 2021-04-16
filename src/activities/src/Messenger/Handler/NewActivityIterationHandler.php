<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\NewActivityIteration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewActivityIterationHandler implements MessageHandlerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(NewActivityIteration $message)
    {
        $activity = $message->getActivity();
        $interval = $activity->getInterval();
        $newActivity = $activity->createNewIterationOfActivity();
        if ($newActivity->getDateEnd() && $newActivity->getDateEnd() <= $interval->getDateEnd()) {
            $this->entityManager->persist($newActivity);
        }
    }
}
