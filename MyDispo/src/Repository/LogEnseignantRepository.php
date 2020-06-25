<?php

namespace App\Repository;

use App\Entity\LogEnseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogEnseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogEnseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogEnseignant[]    findAll()
 * @method LogEnseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogEnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEnseignant::class);
    }

    // /**
    //  * @return LogEnseignant[] Returns an array of LogEnseignant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LogEnseignant
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOrderByDate()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.dateLog', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByEnseignantId($id)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.enseignant = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }
}
