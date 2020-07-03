<?php

namespace App\Repository;

use App\Entity\Creneau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Creneau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creneau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creneau[]    findAll()
 * @method Creneau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreneauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Creneau::class);
    }

     /**
      * @return Creneau[] Returns an array of Creneau objects
      */

    public function findByType($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.type = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }



 public function selectStartEndTitleByType($value)
 {
   return $this->createQueryBuilder('c')
   ->select('c.titre as title','c.dateDebut as start','c.dateFin as end')
   ->where('c.type = :val')
   ->setParameter('val', $value)
   ->getQuery()
   ->getResult()
   ;
 }

 public function selectStartEndTitleByTypeEtEnseignant($value,$id)
 {
   return $this->createQueryBuilder('c')
   ->select('c.titre as title','c.dateDebut as start','c.dateFin as end')
   ->where('c.type = :val')
   ->andWhere('c.enseignant = :id')
   ->setParameter('val', $value)
   ->setParameter('id', $id)
   ->getQuery()
   ->getResult()
   ;
 }


 public function findByTypeEtEnseignant($type,$id)
 {
   $requete=$this->createQueryBuilder('c');
   $requete->andWhere('c.type = :type');
   if($id != null){
   $requete->andWhere('c.enseignant = :id')
   ->setParameter('id', $id);
 }
   $requete->setParameter('type', $type);


   return $requete->getQuery()
   ->getResult()
   ;
 }


    // /**
    //  * @return Creneau[] Returns an array of Creneau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Creneau
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
