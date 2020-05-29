<?php

namespace App\Repository;

use App\Entity\Enseignant;
use App\Entity\Formation;

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

        // Recherche sans critère
        if(sizeof($tab['statut']) > 1 && sizeof($tab['formations']) > 1 && sizeof($tab['saisieFaite']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 ){
        return $requete
            ->getQuery()
            ->getResult()
        ;
        }
        // Recherche seulement sur la saisieFaite
        if(sizeof($tab['statut']) > 1 && sizeof($tab['formations']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['saisieFaite']) < 2){
          $requete->andWhere('e.saisieFaite = :val')
          ->setParameter('val', $tab['saisieFaite']);
        }
        // Recherche seulement sur le statut
        if(sizeof($tab['saisieFaite']) > 1 && sizeof($tab['formations']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['statut']) < 2){
          $requete->andWhere('e.statut = :val')
          ->setParameter('val', $tab['statut']);
        }
        // Recherche seulement sur le mail de relance
        if(sizeof($tab['saisieFaite']) > 1 && sizeof($tab['formations']) > 1 && sizeof($tab['statut']) > 1 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->andWhere('e.mailRelanceRecu = :val')
          ->setParameter('val', $tab['mailRelanceRecu']);
        }
        // Recherche seulement sur les formations
        if(sizeof($tab['saisieFaite']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['statut']) > 1 && sizeof($tab['formations']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->setParameter('val', $tab['formations'][0]->getNomCourt());
        }
        // Recherche seulement sur le statut et le mail de relance
        if(sizeof($tab['saisieFaite']) > 1 && sizeof($tab['formations']) > 1 && sizeof($tab['statut']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->andWhere('e.mailRelanceRecu = :val')
          ->andWhere('e.statut = :val2')
          ->setParameter('val', $tab['mailRelanceRecu'])
          ->setParameter('val2', $tab['statut']);
        }
        // Recherche seulement sur la saisie faite et le mail de relance
        if(sizeof($tab['statut']) > 1 && sizeof($tab['formations']) > 1 && sizeof($tab['saisieFaite']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->andWhere('e.mailRelanceRecu = :val')
          ->andWhere('e.saisieFaite = :val2')
          ->setParameter('val', $tab['mailRelanceRecu'])
          ->setParameter('val2', $tab['saisieFaite']);
        }
        // Recherche seulement sur les formations et le mail de relance
        if(sizeof($tab['saisieFaite']) > 1 && sizeof($tab['statut']) > 1 && sizeof($tab['formations']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->andWhere('e.mailRelanceRecu = :val2')
          ->setParameter('val', $tab['formations'][0]->getNomCourt())
          ->setParameter('val2', $tab['mailRelanceRecu']);
        }
        // Recherche seulement sur les formations et le statut
        if(sizeof($tab['saisieFaite']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['formations']) < 2 && sizeof($tab['statut']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->andWhere('e.statut = :val2')
          ->setParameter('val', $tab['formations'][0]->getNomCourt())
          ->setParameter('val2', $tab['statut']);
        }
        // Recherche seulement sur les formations et la saisie faite
        if(sizeof($tab['statut']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['formations']) < 2 && sizeof($tab['saisieFaite']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->andWhere('e.saisieFaite = :val2')
          ->setParameter('val', $tab['formations'][0]->getNomCourt())
          ->setParameter('val2', $tab['saisieFaite']);
        }
        // Recherche seulement sur la saisie faite et le statut
        if(sizeof($tab['formations']) > 1 && sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['statut']) < 2 && sizeof($tab['saisieFaite']) < 2){
          $requete->andWhere('e.statut = :val')
          ->andWhere('e.saisieFaite = :val2')
          ->setParameter('val', $tab['statut'])
          ->setParameter('val2', $tab['saisieFaite']);
        }
        // Recherche seulement sur les formations et le statut et le saisie faite
        if( sizeof($tab['mailRelanceRecu']) > 1 && sizeof($tab['statut']) < 2 && sizeof($tab['formations']) < 2 && sizeof($tab['saisieFaite']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->andWhere('e.saisieFaite = :val2')
          ->andWhere('e.statut = :val2')
          ->setParameter('val', $tab['formations'][0]->getNomCourt())
          ->setParameter('val2', $tab['saisieFaite'])
          ->setParameter('val3', $tab['statut']);
        }
        // Recherche seulement sur les formations et le statut et le mail de relance
        if( sizeof($tab['saisieFaite'])  > 1 && sizeof($tab['statut']) < 2 && sizeof($tab['formations']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->andWhere('e.mailRelanceRecu = :val2')
          ->andWhere('e.statut = :val2')
          ->setParameter('val', $tab['formations'][0]->getNomCourt())
          ->setParameter('val2', $tab['mailRelanceRecu'])
          ->setParameter('val3', $tab['statut']);
        }
        // Recherche seulement sur les formations et le mail de relance et la saisiefaite
        if(  sizeof($tab['statut']) > 1 && sizeof($tab['saisieFaite']) < 2 && sizeof($tab['formations']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val')
          ->andWhere('e.mailRelanceRecu = :val2')
          ->andWhere('e.saisieFaite = :val2')
          ->setParameter('val', $tab['formations'][0]->getNomCourt())
          ->setParameter('val2', $tab['mailRelanceRecu'])
          ->setParameter('val3', $tab['saisieFaite']);
        }
        // Recherche seulement sur le statut et le mail de relance et la saisiefaite
        if( sizeof($tab['formations']) > 1 &&  sizeof($tab['saisieFaite']) < 2 && sizeof($tab['statut']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->andWhere('e.statut = :val')
          ->andWhere('e.mailRelanceRecu = :val2')
          ->andWhere('e.saisieFaite = :val3')
          ->setParameter('val', $tab['statut'])
          ->setParameter('val2', $tab['mailRelanceRecu'])
          ->setParameter('val3', $tab['saisieFaite']);
        }
        // Recherche seulement sur le statut et le mail de relance et la saisiefaite et les formations
        if( sizeof($tab['formations']) < 2 &&  sizeof($tab['saisieFaite']) < 2 && sizeof($tab['statut']) < 2 && sizeof($tab['mailRelanceRecu']) < 2){
          $requete->leftJoin('e.formations','f')
          ->andWhere('f.nomCourt = :val4')
          ->andWhere('e.mailRelanceRecu = :val2')
          ->andWhere('e.saisieFaite = :val3')
          ->andWhere('e.statut = :val1')
          ->setParameter('val1', $tab['statut'])
          ->setParameter('val2', $tab['mailRelanceRecu'])
          ->setParameter('val3', $tab['saisieFaite'])
          ->setParameter('val4', $tab['formations'][0]->getNomCourt());
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
