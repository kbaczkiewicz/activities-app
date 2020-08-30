<?php


namespace App\Doctrine\EventListener;


use App\Entity\Activity;
use App\Entity\Interval;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UpdateIntervalActivitiesStartDates
{
    public function postUpdate(LifecycleEventArgs $args): void
    {
        /** @var Interval $interval */
        $interval = $args->getObject();
        if (false === $interval instanceof  Interval) {
            return;
        }

        $entityManager = $args->getEntityManager();
        /** @var Activity $activity */
        foreach ($interval->getActivities() as $activity) {
            if ($interval->getDateStart() && $activity->getDateStart() !== $interval->getDateStart()) {
                $activity->setDateStart($interval->getDateStart());
                $entityManager->persist($activity);
            }
        }

        $entityManager->flush();
    }
}
