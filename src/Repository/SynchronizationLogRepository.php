<?php

namespace App\Repository;

use App\Entity\SynchronizationLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SynchronizationLog>
 *
 * @method SynchronizationLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method SynchronizationLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method SynchronizationLog[]    findAll()
 * @method SynchronizationLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SynchronizationLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SynchronizationLog::class);
    }

    public function save(SynchronizationLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SynchronizationLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SynchronizationLog[] Returns an array of SynchronizationLog objects
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

//    public function findOneBySomeField($value): ?SynchronizationLog
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
