<?php

namespace App\Repository;

use App\Entity\Enseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignant[]    findAll()
 * @method Enseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignant::class);
    }

    // /**
    //  * @return Enseignant[] Returns an array of Enseignant objects
    //  */

    public function findByStatut($statut)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.statut = :val')
            ->setParameter('val', $statut)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySaisieFaite($saisieFaite)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.statut = :val')
            ->setParameter('val', $saisieFaite)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBy1($tab)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.saisieFaite = :val')
            ->andWhere('e.statut = :val2')
            ->setParameter('val', $tab['saisieFaite'])
            ->setParameter('val2', $tab['statut'])
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBy0($tab)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.formations','f')
            ->andWhere('e.saisieFaite = :val')
            ->andWhere('e.statut = :val2')
            ->andWhere('f.nomCourt = :val3')
            ->setParameter('val', $tab['saisieFaite'])
            ->setParameter('val2', $tab['statut'])
            ->setParameter('val3', $tab['formations'])
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBy2($tab)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.formations','f')
            ->andWhere('e.saisieFaite = :val')
            ->andWhere('f.nomCourt = :val2')
            ->setParameter('val', $tab['saisieFaite'])
            ->setParameter('val2', $tab['formations'])
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByFormations($formations)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.formations','f')
            ->andWhere('f.nomCourt = :val')
            ->setParameter('val', $formations)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBy4($tab)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.formations','f')
            ->andWhere('e.statut = :val2')
            ->andWhere('f.nomCourt = :val')
            ->setParameter('val', $tab['formations'])
            ->setParameter('val2', $tab['statut'])
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByAll()
    {
        return $this->createQueryBuilder('e')
            ->getQuery()
            ->getResult()
        ;
    }



    /*
    public function findOneBySomeField($value): ?Enseignant
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
