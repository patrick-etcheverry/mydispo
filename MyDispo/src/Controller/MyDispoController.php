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
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\LogEnseignantRepository;
use App\Repository\EnseignantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class MyDispoController extends AbstractController
{

  /**
  * @Route("/saisie-contrainte/{token}", name="saisieContrainte")
  */
  public function index(CreneauRepository $creneauRepository, EnseignantRepository $enseignantRepository,  RemarqueRepository $remarqueRepository,
  FormulaireTitulaireRepository $formulaireTitulaireRepository,FormulaireVacataireRepository $formulaireVacataireRepository, $token)
  {

    //Récupérer l'enseignant ayant le token $token
    $enseignant = $enseignantRepository->findByToken($token)[0];
    // Lien pour la redirection vers le résumé de la saisie
    $lien = $this->generateUrl('resume_saisie',['token'=> $enseignant->getToken()],false);
    //Récupérer les formulaires(paramètrage des formulaires à faire passer dans les vues)
    $formulaireTitulaire = $formulaireTitulaireRepository->findAll()[0];
    $formulaireVacataire = $formulaireVacataireRepository->findAll()[0];

    //RECUPERATION CRENEAUX POUR CALENDRIER MENSUEL

    //CRENEAUX QUI SONT DES EVENEMENTS SPECIAUX (Saisis par l'admin)
    $creneauxEvenement = array();
    $events = $creneauRepository->selectStartEndTitleByType("Evenement");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->rendering="background";
      $object->start=$event["start"]->format("Y-m-d");
      $object->end=$event["end"]->format("Y-m-d");
      $object->allDay=true;
      $object->type="Evenement";
      $object->prio="sansPrio";
      $creneauxEvenement[] = $object;
    }
    //CRENEAUX QUI SONT DES CONTRAINTES PROFESSIONNELLES PONCTUELLES (Saisis par l'enseignant désigné par le token dans l'url)
    $eventsEnseignantPonctu = array();
    $events = $creneauRepository->findByTypeEtEnseignant("ContrainteProPonctu",$enseignant->getId());
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event->getTitre();
      $object->start=$event->getDateDebut()->format("Y-m-d");
      $object->end=$event->getDateFin()->format("Y-m-d");
      $object->allDay=true;
      $object->type="ContrainteProPonctu";
      $object->prio="sansPrio";
      $eventsEnseignantPonctu[] = $object;
    }

    foreach ($creneauxEvenement as $creneauxEvenementCourant) {
      array_push($eventsEnseignantPonctu,$creneauxEvenementCourant);
    }
    $resultPonctu=json_encode($eventsEnseignantPonctu);

    //RECUPERATION CRENEAUX POUR CALENDRIER HEBDO

    //CRENEAUX QUI SONT DES ZONES NON SAISISSABLES(Saisis par l'admin)

    $creneauxGrisee = array();
    $events = $creneauRepository->selectStartEndTitleByType("zoneGrisee");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->rendering="background";
      $object->daysOfWeek=date('w',$event["start"]->getTimestamp());
      $object->startTime=$event["start"]->format("H:i:s");
      $object->endTime=$event["end"]->format("H:i:s");
      $object->type="zoneGrisee";
      $object->prio="sansPrio";
      $creneauxGrisee[] = $object;
    }

    //CRENEAUX QUI SONT DES CONTRAINTES PRO/PERSO HEBDOMADAIRES(Saisis par l'enseignant à qui appartient le token dans l'url)
    $creneauxEnseignant = array();
    $events = $enseignant->getCreneaux();

    foreach ($events as $event){
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
          $object->backgroundColor="#D35400";
          $object->borderColor="#D35400";
          $object->textColor="white";
        }
        if($event->getPrioOuPref() == "Moyenne"){
          $object->backgroundColor="#F39C12";
          $object->borderColor="#F39C12";
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
          $object->borderColor="#2471A3";
          $object->textColor="white";
          $object->backgroundColor="#2471A3";
        }
        if($event->getPrioOuPref() == "Moyenne"){
          $object->borderColor="#3498DB";
          $object->textColor="black";
          $object->backgroundColor="#3498DB";
        }
        if($event->getPrioOuPref() == "Faible"){
          $object->borderColor="#AED6F1";
          $object->textColor="black";
          $object->backgroundColor="#AED6F1";
        }
        break;
        case 'Disponibilite' :
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
      }
      $creneauxEnseignant[] = $object;
    }
    foreach ($creneauxGrisee as $creneauxGriseeCourant) {
      array_push($creneauxEnseignant,$creneauxGriseeCourant);
    }
    $result=json_encode($creneauxEnseignant);


    //RECUPERATION REMARQUES
    $remarques = $enseignant->getRemarques();

    if($remarques[0] != null){
      if($remarques[0]->getType()=="Hebdomadaire"){$remarqueHebdo=$remarques[0]->getContenu();}
      else{$remarquePonctu=$remarques[0]->getContenu();}
    }
    if($remarques[1] != null){
      if($remarques[1]->getType()=="Hebdomadaire"){$remarqueHebdo=$remarques[1]->getContenu();}
      else{$remarquePonctu=$remarques[1]->getContenu();}
    }
    else{
      $remarqueHebdo = "";
      $remarquePonctu = "";
    }

    // $creneauxEnseignantSansGrisee = array();
    // foreach ($creneauxEnseignant as $creneauxEnseignantCourant) {
    //   array_push($creneauxEnseignantSansGrisee,$creneauxEnseignantCourant);      ????????????????????????????????? UTILE POUR LES LOGS ???????????????????????????????????
    // }
    //
    // $result2=json_encode($creneauxEnseignantSansGrisee);


    if($enseignant->getStatut()=='Titulaire'){
      return $this->render('my_dispo/formulaireTit.html.twig', [
        'formulaireTitulaire' => $formulaireTitulaire,
        'events' => $result,
        'enseignant' => $enseignant,
        'lien' => $lien,
        'eventsMensuel' => $resultPonctu,
        // 'eventsSansGrisee' => $result2,
        'remarqueH' => $remarqueHebdo,
        'remarqueP' => $remarquePonctu,
      ]);
    }

    else if($enseignant->getStatut()=='Vacataire'){
      return $this->render('my_dispo/formulaireVac.html.twig',[
        'formulaireVacataire' => $formulaireVacataire,
        'events' => $result,
        //'eventsSansGrisee' => $result2,
        'enseignant' => $enseignant,
        'eventsMensuel' => $resultPonctu,
        'remarqueH' => $remarqueHebdo,
        'remarqueP' => $remarquePonctu,
        'lien' => $lien,

      ]);
    }
  }

  /**
  * @Route("/resume-saisie/{token}", name="resume_saisie")
  */
  public function resumerSaisie(EnseignantRepository $enseignantRepository, CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository,
  FormulaireVacataireRepository $formVacataireRepository, $token)
  {
    $enseignant = $enseignantRepository->findByToken($token)[0];

    //On recupère tous les événements nécessaires à l'affichage d'un calendrier hebdo pour un enseignant (Zones grisées, Contraintes perso, Contraintes pro, Disponibilités)
    $zonesGrisees = array();
    $events = $creneauRepository->selectStartEndTitleByType("zoneGrisee");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->daysOfWeek=date('w',$event["start"]->getTimestamp());
      $object->startTime=$event["start"]->format("H:i:s");
      $object->endTime=$event["end"]->format("H:i:s");
      $zoneGrisees[] = $object;
    }

    $creneauxEnseignant = array();
    $events = $enseignant->getCreneaux();
    foreach ($events as $event){
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
        case 'Disponibilite' :

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
      }
      $creneauxEnseignant[] = $object;
    }

    foreach ($zonesGrisees as $creneauGrise) {
      array_push($creneauxEnseignant,$creneauGrise);
    }
    $result=json_encode($creneauxEnseignant);

    $eventsListe = array();
    $events = $creneauRepository->findByTypeEtEnseignant("ContrainteProPonctu",$enseignant->getId());
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event->getTitre();
      $object->start=$event->getDateDebut()->format("Y-m-d");
      $object->end=$event->getDateFin()->format("Y-m-d");
      $object->allDay=true;
      $eventsListe[] = $object;
    }
    $resultListe=json_encode($eventsListe);

    if($enseignant->getStatut()=="Titulaire"){
      $formulaire = $formTitulaireRepository->findAll()[0];
    }
    else{
      $formulaire=$formVacataireRepository->findAll()[0];
    }

    $echelle = $formulaire->getEchelleCalendrier();
    $heureDebut = $formulaire->getHeureDebutCalendrier();
    $heureFin = $formulaire->getHeureFinCalendrier();
    $lien = $this->generateUrl('saisieContrainte',['token'=> $enseignant->getToken()],false);
    $remarques = $enseignant->getRemarques();

    if($remarques[0] != null){
      if($remarques[0]->getType()=="Hebdomadaire"){$remarqueHebdo=$remarques[0]->getContenu();}
      else{$remarquePonctu=$remarques[0]->getContenu();}
    }
    if($remarques[1] != null){
      if($remarques[1]->getType()=="Hebdomadaire"){$remarqueHebdo=$remarques[1]->getContenu();}
      else{$remarquePonctu=$remarques[1]->getContenu();}
    }
    else{
      $remarqueHebdo = " ";
      $remarquePonctu = " ";
    }

    return $this->render('my_dispo/resume.html.twig', [
      'events' => $result,
      'eventsListe' => $resultListe,
      'echelle' => $echelle,
      'heureDebut' => $heureDebut,
      'heureFin' => $heureFin,
      'lien' => $lien,
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
