<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use StdClass;
use App\Repository\CreneauRepository;
use App\Repository\FormulaireTitulaireRepository;
use App\Repository\FormulaireVacataireRepository;
use App\Repository\EnseignantRepository;

class MyDispoController extends AbstractController
{

  /**
  * @Route("/saisie-contrainte/{token}", name="saisieContrainte")
  */
  /*public function index(EnseignantRepository $enseignantRepository, $token)
  {

    // Récupérer l'objet enseignant ayant le token $token
    $enseignant = $enseignantRepository->findByToken($token);

    // Récupérer les données déjà enregistrées
    $remarquesSaisies = $enseignant->getRemarques();
    $creneauxSaisis = $enseignant->getCreneaux();
    $donneesFormulaire = array();

    // Déterminer le statut de l'enseignant
    if($enseignant->getStatut() == "Titulaire"){
      $form = $this->createForm(FormulaireTitulaireType::class, $donneesFormulaire);

    }
    else {
      $form = $this->createForm(FormulaireVacataireType::class, $donneesFormulaire);


    }

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      //Récupérer les créneaux des 2 calendriers (hebdomadaire et mensuel)


      // Récupérer le gestionnaire d'entité
      $entityManager = $this->getDoctrine()->getManager();

      //Supprimer les remarques en BD (pour les remplacer par celles du formulaire)
      $tabRemarques = $enseignant->getRemarques();
      foreach ($tabRemarques as $remarque) {
        $entityManager->remove($remarque);
      }
      $entityManager->flush();

      //Enregistrer les remarques venant du formulaire
      $remarquesHebdo = new Remarque();
      $remarquesHebdo->setType('Hebdomadaire');
      $remarquesHebdo->setContenu($donneesFormulaire['remarquesHebdo']);
      $remarquesHebdo->setEnseignant($enseignant);
      $entityManager->persist($remarquesHebdo);

      $remarquesPonctuelles = new Remarque();
      $remarquesPonctuelles->setType('Ponctuelle');
      $remarquesPonctuelles->setContenu($donneesFormulaire['remarquesPonctu']);
      $remarquesPonctuelles->setEnseignant($enseignant);
      $entityManager->persist($remarquesPonctuelles);

      $enseignant->addRemarque($remarquesHebdo);
      $enseignant->addRemarque($remarquesPonctuelles);


      $entityManager->flush();



      $entityManager->persist($enseignant);

      // Renvoie l'enseignant vers la page résumant sa saisie avant d'envoyer le mail
      return $this->render('enseignant/new.html.twig', [
          'enseignant' => $enseignant,
          'form' => $form->createView(),
      ]);

    }
    // Afficher la page du formulaire de saisie
    return $this->render('enseignant/new.html.twig', [
        'enseignant' => $enseignant,
        'form' => $form->createView(),
    ]);

  }
*/
  /**
  * @Route("/parametres/generaux", name="parametres_gen")
  */
  public function index2(CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository, FormulaireVacataireRepository $formVacataireRepository)
  {
    $myarray = array();
    $events = $creneauRepository->selectStartEndTitleByType("zoneGrisee");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->daysOfWeek=date('w',$event["start"]->getTimestamp());
      $object->startTime=$event["start"]->format("H:i:s");
      $object->endTime=$event["end"]->format("H:i:s");
      $myarray[] = $object;
    }
    $result=json_encode($myarray);

    $echelle1=$formTitulaireRepository->selectEchelleCalendrier()[0]["echelleCalendrier"];
    $echelle2=$formVacataireRepository->selectEchelleCalendrier()[0]["echelleCalendrier"];
    if($echelle1>$echelle2){$echelle=$echelle2;}
    else{$echelle=$echelle1;}

    return $this->render('my_dispo/index.html.twig', [
      'events' => $result,
      'echelle' => $echelle,
    ]);
  }
}
