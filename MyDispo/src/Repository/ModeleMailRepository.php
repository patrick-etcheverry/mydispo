<?php

namespace App\Repository;

use App\Entity\ModeleMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModeleMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeleMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeleMail[]    findAll()
 * @method ModeleMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeleMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeleMail::class);
    }

    // /**
    //  * @return ModeleMail[] Returns an array of ModeleMail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModeleMail
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
