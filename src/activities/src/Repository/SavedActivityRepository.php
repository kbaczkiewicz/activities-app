<?php

namespace App\Repository;

use App\Entity\SavedActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SavedActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SavedActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SavedActivity[]    findAll()
 * @method SavedActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavedActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedActivity::class);
    }

    // /**
    //  * @return SavedActivity[] Returns an array of SavedActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SavedActivity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
