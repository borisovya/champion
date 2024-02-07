<?php

namespace App\Repository;

use App\Entity\PinPong;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PinPong>
 *
 * @method PinPong|null find($id, $lockMode = null, $lockVersion = null)
 * @method PinPong|null findOneBy(array $criteria, array $orderBy = null)
 * @method PinPong[]    findAll()
 * @method PinPong[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PinPongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PinPong::class);
    }

//    /**
//     * @return PinPong[] Returns an array of PinPong objects
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

//    public function findOneBySomeField($value): ?PinPong
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
