<?php


namespace App\Repository;


use App\Entity\Interval;
use App\Enum\IntervalStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class IntervalRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Interval::class));
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Interval
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

    public function findOneBy(array $criteria, array $orderBy = null): ?Interval
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @return Interval[]
     */
    public function findOverdoneIntervals(): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.dateEnd < :currentDate')
            ->setParameter('currentDate', new \DateTime())
            ->andWhere('i.status = :ongoing')
            ->setParameter('ongoing', IntervalStatus::STATUS_STARTED)
            ->getQuery()->getResult();
    }

    /**
     * @return Interval[]
     */
    public function getIntervalsToStart(): array
    {
        $qb = $this->createQueryBuilder('i');

        return $qb
            ->where('i.dateStart <= :currentDate')
            ->setParameter('currentDate', new \DateTime())
            ->andWhere($qb->expr()->orX('i.status = :saved', 'i.status = :new', 'i.status = :draft'))
            ->setParameter('saved', IntervalStatus::STATUS_SAVED)
            ->setParameter('draft', IntervalStatus::STATUS_SAVED)
            ->setParameter('new', IntervalStatus::STATUS_SAVED)
            ->getQuery()->getResult();
    }

}
