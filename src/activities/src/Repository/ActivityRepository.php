<?php


namespace App\Repository;


use App\Entity\Activity;
use App\Entity\Interval;
use App\Entity\User;
use App\Enum\ActivityStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class ActivityRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Activity::class));
    }

    public function find($id, $lockMode = null, $lockVersion = null): Activity
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, array $orderBy = null): Activity
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @return Activity[]
     */
    public function findActivitiesToMarkAsFailed(): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.type', 't')
            ->where('DATE_ADD(a.dateStart, t.daysSpan, \'day\')  < :currentDate')
            ->setParameter('currentDate', new \DateTime())
            ->andWhere('a.status = :ongoingStatus')
            ->setParameter('ongoingStatus', ActivityStatus::STATUS_PENDING)
            ->andWhere('a.interval IS NOT NULL')
            ->getQuery()->getResult();
    }

    public function findActivitiesToStart(): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.type', 't')
            ->where('a.dateStart >= :currentDate')
            ->setParameter('currentDate', new \DateTime())
            ->andWhere('a.status = :createdStatus')
            ->setParameter('createdStatus', ActivityStatus::STATUS_CREATED)
            ->andWhere('a.interval IS NOT NULL')
            ->getQuery()->getResult();
    }

    public function findUniqueByInterval(Interval $interval): array
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.interval', 'i')
            ->andwhere('a.interval = :intervalId')
            ->setParameters(['intervalId' => $interval->getId()])
            ->andWhere('a.first = :first')
            ->setParameter('first', true)
            ->orderBy('a.dateStart')
            ->getQuery()->getResult();
    }

    public function findCompletedActivities(Interval $interval): array
    {
        return $this->findActivitiesForIntervalAndStatus($interval, ActivityStatus::STATUS_COMPLETED);
    }

    public function findFailedActivities(Interval $interval): array
    {
        return $this->findActivitiesForIntervalAndStatus($interval, ActivityStatus::STATUS_FAILED);
    }

    public function findCompletedActivitiesForUser(User $user)
    {
        return $this->findActivitiesForUserAndStatus($user, ActivityStatus::STATUS_COMPLETED);
    }

    public function findFailedActivitiesForUser(User $user)
    {
        return $this->findActivitiesForUserAndStatus($user, ActivityStatus::STATUS_FAILED);
    }

    private function findActivitiesForIntervalAndStatus(Interval $interval, string $status): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.interval = :interval')
            ->andWhere('a.status = :status')
            ->setParameters(['interval' => $interval, 'status' => $status])
            ->getQuery()->getResult();
    }

    private function findActivitiesForUserAndStatus(User $user, string $status): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.interval', 'i')
            ->where('i.user = :user')
            ->andWhere('a.status = :status')
            ->setParameters(['user' => $user, 'status' => $status])
            ->getQuery()->getResult();
    }

}
