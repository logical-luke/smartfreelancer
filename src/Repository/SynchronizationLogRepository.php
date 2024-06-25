<?php

namespace App\Repository;

use App\Entity\SynchronizationLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

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

    public function findOneById(Uuid $id): ?SynchronizationLog
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
