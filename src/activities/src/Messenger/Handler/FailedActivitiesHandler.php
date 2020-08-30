<?php


namespace App\Messenger\Handler;


use App\Enum\ActivityStatus;
use App\Messenger\Message\FailedActivities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FailedActivitiesHandler implements MessageHandlerInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(FailedActivities $message)
    {
        foreach ($message->getActivities() as $activity) {
            $activity->setStatus(ActivityStatus::STATUS_FAILED);
            $this->entityManager->persist($activity);
            $interval = $activity->getInterval();
            $newActivity = $activity->createNewIterationOfActivity();
            if ($newActivity->getDateEnd() && $newActivity->getDateEnd() < $interval->getDateEnd()) {
                $this->entityManager->persist($newActivity);
            }
        }

        $this->entityManager->flush();
        /** @todo implement push message */
    }
}
