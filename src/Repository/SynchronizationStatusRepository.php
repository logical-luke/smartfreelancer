<?php

namespace App\Repository;

use App\Entity\SynchronizationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SynchronizationStatus>
 *
 * @method SynchronizationStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method SynchronizationStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method SynchronizationStatus[]    findAll()
 * @method SynchronizationStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SynchronizationStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SynchronizationStatus::class);
    }

    public function save(SynchronizationStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SynchronizationStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SynchronizationStatus[] Returns an array of SynchronizationStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SynchronizationStatus
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
