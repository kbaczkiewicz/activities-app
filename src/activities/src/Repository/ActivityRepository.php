<?php


namespace App\Repository;


use App\Entity\Activity;
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
    public function getActivitiesToMarkAsFailed(): array
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

}
