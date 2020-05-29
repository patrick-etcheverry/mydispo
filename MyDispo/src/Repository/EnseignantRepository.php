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


    public function findByGeneral($tab)
    {
        $requete= $this->createQueryBuilder('e');

          if(array_key_exists('saisieFaite', $tab) && array_key_exists('statut', $tab) && array_key_exists('formations', $tab)){
            $requete->leftJoin('e.formations','f')
            ->andWhere('e.saisieFaite = :val')
            ->andWhere('e.statut = :val2')
            ->andWhere('f.nomCourt = :val3')
            ->setParameter('val', $tab['saisieFaite'])
            ->setParameter('val2', $tab['statut'])
            ->setParameter('val3', $tab['formations']);
          }
          if(array_key_exists('saisieFaite', $tab) && array_key_exists('statut', $tab) && array_key_exists('formations', $tab) == false ){

            $requete->andWhere('e.saisieFaite = :val')
            ->andWhere('e.statut = :val2')
            ->setParameter('val', $tab['saisieFaite'])
            ->setParameter('val2', $tab['statut']);
          }
          if(array_key_exists('saisieFaite', $tab) && array_key_exists('formations', $tab) && array_key_exists('statut', $tab) == false){
            $requete->leftJoin('e.formations','f')
            ->andWhere('e.saisieFaite = :val')
            ->andWhere('f.nomCourt = :val2')
            ->setParameter('val', $tab['saisieFaite'])
            ->setParameter('val2', $tab['formations']);
          }
          if(array_key_exists('statut', $tab) && array_key_exists('formations', $tab) && array_key_exists('saisieFaite', $tab) == false){
            $requete->leftJoin('e.formations','f')
            ->andWhere('e.statut = :val2')
            ->andWhere('f.nomCourt = :val')
            ->setParameter('val', $tab['formations'])
            ->setParameter('val2', $tab['statut']);
          }
          if(array_key_exists('saisieFaite', $tab) && array_key_exists('formations', $tab) == false && array_key_exists('statut', $tab) == false){
            $requete->andWhere('e.saisieFaite = :val')
            ->setParameter('val', $tab['saisieFaite']);
          }
          if(array_key_exists('statut', $tab) && array_key_exists('formations', $tab) == false && array_key_exists('saisieFaite', $tab) == false){
            $requete->andWhere('e.statut = :val')
            ->setParameter('val', $tab['statut']);
          }
          if(array_key_exists('formations', $tab) && array_key_exists('statut', $tab) == false && array_key_exists('saisieFaite', $tab) == false){
            $requete->leftJoin('e.formations','f')
            ->andWhere('f.nomCourt = :val')
            ->setParameter('val', $tab['formations']);
          }
        return $requete
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
