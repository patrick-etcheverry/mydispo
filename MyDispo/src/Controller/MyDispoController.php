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
use Symfony\Component\Validator\Constraints\DateTime;


class MyDispoController extends AbstractController
{

  /**
  * @Route("/", name="redirectionAdmin")
  */
  public function goToAdmin(){
    return $this->render('my_dispo/accueil.html.twig');
  }



  /**
  * @Route("/saisie-contrainte/{token}", name="saisieContrainte")
  */
  public function index(CreneauRepository $creneauRepository, EnseignantRepository $enseignantRepository,  RemarqueRepository $remarqueRepository,
  FormulaireTitulaireRepository $formulaireTitulaireRepository,FormulaireVacataireRepository $formulaireVacataireRepository, $token)
  {

    // Dans l'url on récupère le token en clair => on le crypte pour trouver l'enseignant correspondant en BD
    $enseignantTemporairePourAccederFonctionCryptage = new Enseignant();
    $tokenCrypte = $enseignantTemporairePourAccederFonctionCryptage->crypterToken($token);

    //Récupérer l'enseignant ayant le token $token
    $enseignant = $enseignantRepository->findOneByToken($tokenCrypte);

    // Vérifier qu'on a trouvé un enseignant avec ce token
    if(!$enseignant)
    {
      // redirection vers une page indiquant que l'URL est invalide
      return $this->render('my_dispo/tokenInvalide.html.twig');
    }
    // Lien pour la redirection vers le résumé de la saisie
    $lien = $this->generateUrl('resume_saisie',['token'=> $enseignant->getToken()],false);

    //Récupérer les formulaires(paramètrage des formulaires à faire passer dans les vues)
    $formulaireTitulaire = $formulaireTitulaireRepository->findAll()[0];
    $formulaireVacataire = $formulaireVacataireRepository->findAll()[0];

    //Récupérer le regroupement des enseignenements de l'enseignant
    $regroupement=$enseignant->getGrouperEnseignements();
    //RECUPERATION CRENEAUX POUR CALENDRIER MENSUEL

    //CRENEAUX QUI SONT DES EVENEMENTS SPECIAUX (Saisis par l'admin)
    $creneauxEvenement = array();
    $events = $creneauRepository->selectStartEndTitleByType("Evenement");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->rendering="background";
      $object->overlap=false;
      $object->backgroundColor="#000000";
      $object->start=$event["start"]->format("Y-m-d");
      $object->end=$event["end"]->format("Y-m-d");
      $object->type="Evenement";
      $object->prio="sansPrio";
      $creneauxEvenement[] = $object;
    }

    //CRENEAUX QUI SONT DES CONTRAINTES PROFESSIONNELLES PONCTUELLES (Saisis par l'enseignant désigné par le token dans l'url)
    $eventsEnseignantPonctu = array();
    $events = $creneauRepository->selectStartEndTitleByTypeEtEnseignant("ContrainteProPonctu",$enseignant->getId());
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->start=$event["start"]->format("Y-m-d");
      $object->end=$event["end"]->format("Y-m-d");
      $object->type="ContrainteProPonctu";
      $object->prio="sansPrio";
      $eventsEnseignantPonctu[] = $object;
    }

    $creneauxEnseignantSansGrisee = array();
    foreach ($eventsEnseignantPonctu as $eventEnseignantPonctu) {
     array_push($creneauxEnseignantSansGrisee,$eventEnseignantPonctu);
    }





    foreach ($creneauxEvenement as $creneauEvenementCourant) {
      array_push($eventsEnseignantPonctu,$creneauEvenementCourant);
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
      $object->overlap=false;
      $object->backgroundColor="#000000";
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
      if($event->getType() != "ContrainteProPonctu"){
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
          $object->backgroundColor="#F1C40F";
          $object->borderColor="#F1C40F";
          $object->textColor="black";
        }
      }
      $creneauxEnseignant[] = $object;
    }
  }

    foreach ($creneauxEnseignant as $creneauEnseignantCourant) {
     array_push($creneauxEnseignantSansGrisee,$creneauEnseignantCourant);
    }

    foreach ($creneauxGrisee as $creneauxGriseeCourant) {
      array_push($creneauxEnseignant,$creneauxGriseeCourant);
    }
    $result=json_encode($creneauxEnseignant);
  $result2=json_encode($creneauxEnseignantSansGrisee);

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
      $remarqueHebdo = " ";
      $remarquePonctu = " ";
    }






    if($enseignant->getStatut()== "Titulaire" && $formulaireTitulaire->getEstOuvert() == true){
      return $this->render('my_dispo/formulaireTit.html.twig', [
        'formulaireTitulaire' => $formulaireTitulaire,
        'events' => $result,
        'enseignant' => $enseignant,
        'lien' => $lien,
        'eventsMensuel' => $resultPonctu,
        'eventsSansGrisee' => $result2,
        'remarqueH' => $remarqueHebdo,
        'remarqueP' => $remarquePonctu,
        'regrouperEnseignements' => $regroupement,
      ]);
    }

    if($enseignant->getStatut()=="Vacataire" && $formulaireVacataire->getEstOuvert() == true){
      return $this->render('my_dispo/formulaireVac.html.twig',[
        'formulaireVacataire' => $formulaireVacataire,
        'events' => $result,
        'eventsSansGrisee' => $result2,
        'enseignant' => $enseignant,
        'eventsMensuel' => $resultPonctu,
        'remarqueH' => $remarqueHebdo,
        'remarqueP' => $remarquePonctu,
        'lien' => $lien,
        'regrouperEnseignements' => $regroupement,
      ]);
    }
    else{
      return $this->render('my_dispo/formulaireFerme.html.twig');

    }
  }

  /**
  * @Route("/resume-saisie/{token}", name="resume_saisie")
  */
  public function resumerSaisie(EnseignantRepository $enseignantRepository, CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository,
  FormulaireVacataireRepository $formVacataireRepository, $token)
  {
    $entityManager = $this->getDoctrine()->getManager();

    // Dans l'url on récupère le token en clair => on le crypte pour trouver l'enseignant correspondant en BD
    $enseignantTemporairePourAccederFonctionCryptage = new Enseignant();
    $tokenCrypte = $enseignantTemporairePourAccederFonctionCryptage->crypterToken($token);

    //Récupérer l'enseignant ayant le token $token
    $enseignant = $enseignantRepository->findOneByToken($tokenCrypte);

    //Récupérer le regroupement des enseignements de l'enseignant
    $regroupement = $enseignant->getGrouperEnseignements();


    if($enseignant->getSaisieFaite() == false){
      $enseignant->setSaisieFaite(true);
      $enseignant->setDateSaisie(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
    }
    $enseignant->setDateDerniereModif(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

    $entityManager->persist($enseignant);
    $entityManager->flush();




    // //On recupère tous les événements nécessaires à l'affichage d'un calendrier hebdo pour un enseignant (Contraintes perso, Contraintes pro, Disponibilités)


    $creneauxEnseignant = array();
    $events = $enseignant->getCreneaux();
    foreach ($events as $event){
      if($event->getType() != "ContrainteProPonctu"){
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
          $object->backgroundColor="#F1C40F";
          $object->borderColor="#F1C40F";
          $object->textColor="black";
        }
      }
      $creneauxEnseignant[] = $object;
    }
  }

    $result=json_encode($creneauxEnseignant);

    $eventsListe = array();
    $events = $creneauRepository->findByTypeEtEnseignant("ContrainteProPonctu",$enseignant->getId());
    foreach($events as $event){
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



    // ENVOI DU MAIL A L'ADMIN
	  $titrecontrainte = "";
    $creneaux = $enseignant->getCreneaux();
    $remarques = $enseignant->getRemarques();
    $sujetMail = $_ENV['PREFIX_MAIL'] . "Résumé de la saisie de ".$enseignant->getPrenom()." ".$enseignant->getNom()." - IUT Bayonne";
    $contenu = "Résumé de la saisie de ".$enseignant->getPrenom()." ".$enseignant->getNom()." : " . PHP_EOL .PHP_EOL .PHP_EOL;

    $contenu .= "Contraintes hebdomadaires : " . PHP_EOL .PHP_EOL;
    foreach ($creneaux as $creneauxCourant) {
      if($creneauxCourant->getType() == "ContraintePro" || $creneauxCourant->getType() == "ContraintePerso" || $creneauxCourant->getType() == "Disponibilite"){
      	$titrecontrainte = trim($creneauxCourant->getTitre());
      	if (($creneauxCourant->getType() == "ContraintePerso") && ($titrecontrainte == "")) {	$titrecontrainte = "Contrainte personnelle "; }
      	if (($creneauxCourant->getType() == "Disponibilite") && ($titrecontrainte == "")) {	$titrecontrainte = "Disponibilité "; }
        $contenu .= "- Titre : ". $titrecontrainte .", Priorité : ".$creneauxCourant->getPrioOuPref().", Type : " . $creneauxCourant->getType() . ", Date de début : "
        .$creneauxCourant->getDateDebut()->format('d-m-Y à H:i').", Date de fin : ".$creneauxCourant->getDateFin()->format('d-m-Y à H:i').". " . PHP_EOL .PHP_EOL;
      }
    }

    $contenu .= PHP_EOL . " Remarques sur les contraintes hebdomadaires : " . PHP_EOL . PHP_EOL;
    foreach ($remarques as $remarquesCourant) {
      if($remarquesCourant->getType() == "Hebdomadaire"){
        $contenu .= "- Contenu : ".trim($remarquesCourant->getContenu()).". " . PHP_EOL .PHP_EOL;
      }
    }

    $contenu .= PHP_EOL . " Contraintes ponctuelles : " . PHP_EOL .PHP_EOL;
    foreach ($creneaux as $creneauxCourant) {
      if($creneauxCourant->getType() == "ContrainteProPonctu"){
        $contenu .= "- Titre : ".trim($creneauxCourant->getTitre()).", Date : "
        .$creneauxCourant->getDateDebut()->format('d-m-Y').". " . PHP_EOL . PHP_EOL;
      }
    }

    $contenu .= PHP_EOL . " Remarques sur les contraintes ponctuelles : " . PHP_EOL .PHP_EOL;
    foreach ($remarques as $remarquesCourant) {
      if($remarquesCourant->getType() == "Ponctuelle"){
        $contenu .= "- Contenu : ". trim($remarquesCourant->getContenu()).". " . PHP_EOL .PHP_EOL;
      }
    }

    $contenu .= PHP_EOL . " Préférence sur le regroupement des enseignements : " . PHP_EOL .PHP_EOL;
    $contenu .= "- ".$enseignant->getGrouperEnseignements().". "  . PHP_EOL . PHP_EOL .PHP_EOL;


           $transport = (new \Swift_SmtpTransport($_ENV['ADRESS_SERVER_SMTP'],$_ENV['SERVER_SMTP_PORT']))
              ->setEncryption($_ENV['ENCRYPTION_SERVER_SMTP'])
              ->setAuthMode('login')
              ->setUsername($_ENV['LOGIN_SMTP'])
              ->setPassword($_ENV['PASSWORD_SMTP']);
          $mailer = new \Swift_Mailer($transport);
          $message = (new \Swift_Message($sujetMail))
             ->setFrom($_ENV['ADMIN_MAIL'])
             ->setTo($_ENV['MAIL_SENDER'])
	         ->setReplyTo($_ENV['ADMIN_MAIL'])
             ->setBody($contenu);
          $mailer->send($message);



    return $this->render('my_dispo/resume.html.twig', [
      'events' => $result,
      'enseignant' => $enseignant,
      'eventsListe' => $resultListe,
      'echelle' => $echelle,
      'heureDebut' => $heureDebut,
      'heureFin' => $heureFin,
      'lien' => $lien,
      'remarqueH' => $remarqueHebdo,
      'remarqueP' => $remarquePonctu,
      'regrouperEnseignements' => $regroupement,
    ]);
  }






  /**
  * @Route("/admin/horaires", name="horaires_non_saisissables")
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
  * @Route("/admin/evenements", name="evenements", methods={"GET","POST"})
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
* @Route("/admin/confirmationChangementAnnee", name="confirmationChangementAnnee")
*/
public function confirmationChangementAnnee()
{
  return $this->render('my_dispo/confirmationChangementAnnee.html.twig');
}

  /**
  * @Route("/admin/ChangementAnnee", name="changement_annee")
  */
  public function ChangementAnnee(EnseignantRepository $repoEnseignant, LogEnseignantRepository $repoLogs, CreneauRepository $repoCreneau, RemarqueRepository $repoRemarque)
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


      /* Supprimer les remarques ponctuelles (on conserve les remarques hebdomadaires)*/
      $tabRemarques = $repoRemarque->findByEnseignant($enseignantCourant);
      foreach ($tabRemarques as $remarque) {
        if($remarque->getType() == "Ponctuelle"){
          $remarque->setContenu("");
          $entityManager->persist($remarque);
          //$enseignantCourant->removeRemarque($remarque);
        }
      }

      /* Supprimer les créneaux ponctuels de l'enseignant */
      $tabCreneauxPonctuels = $repoCreneau->findByEnseignant($enseignantCourant);
      foreach ($tabCreneauxPonctuels as $creneauxCourant) {
        if($creneauxCourant->getType() == "ContrainteProPonctu"){
          $entityManager->remove($creneauxCourant);
          $enseignantCourant->removeCreneaux($creneauxCourant);
        }
      }

      $entityManager->persist($enseignantCourant);
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

    return $this->render('my_dispo/changementAnnee.html.twig');
  }

}
