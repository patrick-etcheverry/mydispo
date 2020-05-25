<?php

namespace App\Repository;

use App\Entity\Remarque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Remarque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Remarque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Remarque[]    findAll()
 * @method Remarque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemarqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Remarque::class);
    }

    // /**
    //  * @return Remarque[] Returns an array of Remarque objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Remarque
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
