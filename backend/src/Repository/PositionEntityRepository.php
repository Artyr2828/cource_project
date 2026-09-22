<?php

namespace App\Repository;

use App\Entity\PositionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PositionEntity>
 */
class PositionEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PositionEntity::class);
        
    }

    public function findPaginated(?int $before, ?int $after, int $limit){
        $qb = $this->createQueryBuilder('p')->setMaxResults($limit + 1);

        if ($after !== 0){
            $qb = $qb->andWhere('p.id > :after')
            ->setParameter('after', $after)
            ->orderBy('p.id', 'ASC');
        } else if ($before !== 0){
            $qb = $qb->andWhere('p.id < :before')
            ->setParameter('before', $before)
            ->orderBy('p.id', 'DESC');
        } else {
            $qb = $qb->orderBy('p.id', 'ASC'); 
        }
        $positions = $qb->getQuery()->getResult();

        if ($before !== 0) {
            $positions = array_reverse($positions);
        }
        return $positions;
    }

    //    /**
    //     * @return PositionEntity[] Returns an array of PositionEntity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PositionEntity
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
