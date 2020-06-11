<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use StdClass;
use App\Entity\Enseignant;
use App\Entity\Creneau;
use App\Entity\LogEnseignant;
use App\Entity\Remarque;
use App\Repository\CreneauRepository;
use App\Repository\RemarqueRepository;
use App\Repository\FormulaireTitulaireRepository;
use App\Repository\FormulaireVacataireRepository;

use App\Repository\LogEnseignantRepository;
use App\Repository\EnseignantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;




class MyDispoController extends AbstractController
{

  /**
  * @Route("/saisie-contrainte/{token}", name="saisieContrainte")
  */
  public function index(CreneauRepository $creneauRepository, EnseignantRepository $enseignantRepository,  RemarqueRepository $remarqueRepository, FormulaireTitulaireRepository $formulaireTitulaireRepository, $token, Request $request)
  {

    // Récupérer l'objet enseignant ayant le token $token
    $enseignant = $enseignantRepository->findByToken($token);
    $formulaireTitulaire = $formulaireTitulaireRepository->findAll();

    $creneauxEvenement = array();
    $events = $creneauRepository->selectStartEndTitleByType("evenement");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->rendering="background";
      $object->start=$event["start"]->format("Y-m-d H:i:s");
      $object->end=$event["end"]->format("Y-m-d H:i:s");
      $creneauxEvenement[] = $object;
    }

    $eventsEnseignantPonctu = array();
    $events = $creneauRepository->findByTypeEtEnseignant("ContrainteProPonctu",$enseignant[0]->getId());
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event->getTitre();
      $object->start=$event->getDateDebut()->format("Y-m-d H:i:s");
      $object->end=$event->getDateFin()->format("Y-m-d H:i:s");
      $eventsEnseignantPonctu[] = $object;
    }

    foreach ($creneauxEvenement as $creneauxEvenementCourant) {
      array_push($eventsEnseignantPonctu,$creneauxEvenementCourant);
    }


    $resultPonctu=json_encode($eventsEnseignantPonctu);

    $creneauxGrisee = array();
    $events = $creneauRepository->selectStartEndTitleByType("zoneGrisee");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->rendering="background";
      $object->daysOfWeek=date('w',$event["start"]->getTimestamp());
      $object->startTime=$event["start"]->format("H:i:s");
      $object->endTime=$event["end"]->format("H:i:s");
      $creneauxGrisee[] = $object;
    }
    $creneauxEnseignant = array();
    $events = $enseignant[0]->getCreneaux();




    foreach ($events as $event){
      if($event->getType() == "ContraintePerso" || $event->getType() == "ContraintePro" ){
      $object = new StdClass;
      $object->title=$event->getTitre();
      $object->daysOfWeek=date('w',$event->getDateDebut()->getTimestamp());
      $object->startTime=$event->getDateDebut()->format("H:i:s");
      $object->endTime=$event->getDateFin()->format("H:i:s");
      $object->prio=$event->getPrioOuPref();
      $object->type=$event->getType();
      switch ($event->getType()) {
        case 'ContraintePro':
          if($event->getPrioOuPref() == "Forte"){
            $object->backgroundColor="#B84331";
            $object->borderColor="#B84331";
            $object->textColor="black";
          }
          if($event->getPrioOuPref() == "Moyenne"){
            $object->backgroundColor="orange";
            $object->borderColor="orange";
            $object->textColor="black";
          }
          if($event->getPrioOuPref() == "Faible"){
            $object->backgroundColor="#FFD433";
            $object->borderColor="#FFD433";
            $object->textColor="black";
          }
          break;
        case 'ContraintePerso' :

          if($event->getPrioOuPref() == "Forte"){
            $object->borderColor="#8A47A9";
            $object->textColor="black";
            $object->backgroundColor="#8A47A9";
          }
          if($event->getPrioOuPref() == "Moyenne"){
            $object->borderColor="#314AB8";
            $object->textColor="black";
            $object->backgroundColor="#314AB8";
          }
          if($event->getPrioOuPref() == "Faible"){
            $object->borderColor="#2EAED3";
            $object->textColor="black";
            $object->backgroundColor="#2EAED3";
          }
        break;
      }

      $creneauxEnseignant[] = $object;
    }}

    foreach ($creneauxGrisee as $creneauxGriseeCourant) {
      array_push($creneauxEnseignant,$creneauxGriseeCourant);
    }

    $result=json_encode($creneauxEnseignant);

    // Récupérer les données déjà enregistrées
    $remarquesSaisies = $enseignant[0]->getRemarques();
    $remarqueHebdo=  $remarquesSaisies[0]->getContenu();
    $remarquePonctu=  $remarquesSaisies[1]->getContenu();
    $creneauxSaisis = $enseignant[0]->getCreneaux();
    $donneesFormulaire = array();

    // Déterminer le statut de l'enseignant
    if($enseignant[0]->getStatut() == "Titulaire"){
      $defaultData = ['message' => 'Type your message here'];
      $form = $this->createFormBuilder($defaultData)
          ->add('remarquesHebdo', TextareaType::class, array(
                  'label' => 'Remarques éventuelles'
              ))
          ->add('remarquesPonctu', TextareaType::class, array(
                      'label' => 'Remarques éventuelles'
                  ))
      ->getForm();

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
      return $this->render('my_dispo/resumeSaisie.html.twig',
      [
          'enseignant' => $enseignant,
          'form' => $form->createView(),
      ]
    );

    }
    // Afficher la page du formulaire de saisie
    return $this->render('my_dispo/formulaireTit.html.twig', [
        'formulaireTitulaire' => $formulaireTitulaire[0],
        'form' => $form->createView(),
        'events' => $result,
        'enseignant' => $enseignant[0],
        'eventsMensuel' => $resultPonctu,
        'remarqueH' => $remarqueHebdo,
        'remarqueP' => $remarquePonctu,
    ]);

  }

  /**
  * @Route("/horaires", name="horaires_non_saisissables")
  */
  public function horaires(CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository, FormulaireVacataireRepository $formVacataireRepository)
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

    $heureDebut1=$formTitulaireRepository->selectHeureDebutCalendrier()[0]["heureDebutCalendrier"];
    $heureDebut2=$formVacataireRepository->selectHeureDebutCalendrier()[0]["heureDebutCalendrier"];
    if($heureDebut1>$heureDebut2){$heureDebut=$heureDebut2;}

    else{$heureDebut=$heureDebut1;}

    $heureFin1=$formTitulaireRepository->selectHeureFinCalendrier()[0]["heureFinCalendrier"];
    $heureFin2=$formVacataireRepository->selectHeureFinCalendrier()[0]["heureFinCalendrier"];
    if($heureFin1>$heureFin2){$heureFin=$heureFin1;}

    else{$heureFin=$heureFin2;}



    return $this->render('my_dispo/horairesNonSaisissables.html.twig', [
      'events' => $result,
      'echelle' => $echelle,
      'heureDebut' => $heureDebut,
      'heureFin' => $heureFin,
    ]);
  }

  /**
  * @Route("/evenements", name="evenements", methods={"GET","POST"})
  */
  public function evenements(CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository, FormulaireVacataireRepository $formVacataireRepository)
  {
    $myarray = array();
    $events = $creneauRepository->selectStartEndTitleByType("evenement");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->start=$event["start"]->format("Y-m-d");
      $object->end=$event["end"]->format("Y-m-d");
      $myarray[] = $object;
    }
    $result=json_encode($myarray);



    return $this->render('my_dispo/evenementsSpeciaux.html.twig', [
      'events' => $result,
    ]);
  }



  /**
  * @Route("/ChangementAnnee", name="changement_annee")
  */
  public function ChangementAnnee(EnseignantRepository $repoEnseignant, LogEnseignantRepository $repoLogs, CreneauRepository $repoCreneau)
  {
    $entityManager = $this->getDoctrine()->getManager();

    $enseignants = $repoEnseignant->findAll();
    foreach ($enseignants as $enseignantCourant) {
      /* Réinitialiser les informations relatives aux mails envoyés à chaque enseignant
      et enregistrer le fait qu'aucune saisie n'a été réalisée */
      $enseignantCourant->setSaisieFaite(false);
      $enseignantCourant->setDateSaisie(null);
      $enseignantCourant->setPremierMailRecu(false);
      $enseignantCourant->setDatePremierMail(null);
      $enseignantCourant->setMailRelanceRecu(false);
      $enseignantCourant->setDateDerniereRelance(null);
      $enseignantCourant->setDateDerniereModif(null);
      $enseignantCourant->setNbRelance(0);
      $entityManager->persist($enseignantCourant);

     /* Supprimer les remarques ponctuelles (on conserver les remarques hebdomadaires)*/
      $tabRemarques = $enseignantCourant->getRemarques();
      foreach ($tabRemarques as $remarque) {
        if($remarque->getType() == "Ponctuelle"){
          $entityManager->remove($remarque);
        }
      }
      $entityManager->flush();
  }

   /* Supprimer les événements créés par l'admin */
   $tabEvenementsAdmin = $repoCreneau->findByType("Evenement");
   foreach ($tabEvenementsAdmin as $EvenementCourant) {
     $entityManager->remove($EvenementCourant);
   }
   $entityManager->flush();

   /* Supprimer tous les logs des enseignants */
   $tabLogsEnseignants = $repoLogs->findAll();
   foreach ($tabLogsEnseignants as $logEnseignant) {
     $entityManager->remove($logEnseignant);
   }
   $entityManager->flush();

    return $this->render('my_dispo/confirmationChangementAnnee.html.twig');
  }

}