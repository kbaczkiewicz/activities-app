<?php


namespace App\Repository;


use App\Entity\ActivityType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityTypeRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(ActivityType::class));
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?ActivityType
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

    public function findOneBy(array $criteria, array $orderBy = null): ?ActivityType
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function findByUserWithGeneral(?UserInterface $user): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orWhere('a.user IS NULL')
            ->getQuery()->getResult();
    }

}
