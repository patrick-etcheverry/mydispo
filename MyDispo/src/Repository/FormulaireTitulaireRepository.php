<?php

namespace App\Repository;

use App\Entity\FormulaireTitulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormulaireTitulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulaireTitulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulaireTitulaire[]    findAll()
 * @method FormulaireTitulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireTitulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulaireTitulaire::class);
    }





    public function selectEchelleCalendrier()
    {
      return $this->createQueryBuilder('f')
      ->select('f.echelleCalendrier')
      ->getQuery()
      ->getResult()
      ;
    }

    public function selectHeureDebutCalendrier()
    {
      return $this->createQueryBuilder('f')
      ->select('f.heureDebutCalendrier')
      ->getQuery()
      ->getResult()
      ;
    }

    public function selectHeureFinCalendrier()
    {
      return $this->createQueryBuilder('f')
      ->select('f.heureFinCalendrier')
      ->getQuery()
      ->getResult()
      ;
    }



    // /**
    //  * @return FormulaireTitulaire[] Returns an array of FormulaireTitulaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormulaireTitulaire
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
