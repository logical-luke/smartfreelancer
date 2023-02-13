<?php

namespace App\Repository;

use App\Entity\TimeEntry;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<TimeEntry>
 *
 * @method TimeEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeEntry[]    findAll()
 * @method TimeEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeEntry::class);
    }

    public function save(TimeEntry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TimeEntry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByEndTime(User $user, DateTimeInterface $endDate): array
    {
        $startTime = $endDate->setTime(0, 0);
        $endTime = $endDate->setTime(23, 59, 59);

        return $this->createQueryBuilder('te')
            ->where('te.owner = :user')
            ->andWhere('te.endTime > :startDate')
            ->andWhere('te.endTime < :endDate')
            ->setParameter('user', $user->getId(), UuidType::NAME)
            ->setParameter('startDate', $startTime)
            ->setParameter('endDate', $endTime)
            ->getQuery()
            ->getResult();
    }
}
