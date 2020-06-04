<?php

namespace App\Repository;

use App\Entity\FormulaireVacataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormulaireVacataire|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulaireVacataire|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulaireVacataire[]    findAll()
 * @method FormulaireVacataire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireVacataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulaireVacataire::class);
    }



    public function selectEchelleCalendrier()
    {
      return $this->createQueryBuilder('f')
      ->select('f.echelleCalendrier')
      ->getQuery()
      ->getResult()
      ;
    }
    
    // /**
    //  * @return FormulaireVacataire[] Returns an array of FormulaireVacataire objects
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
    public function findOneBySomeField($value): ?FormulaireVacataire
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
