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
use App\Repository\FormulaireTitulaireRepository;
use App\Repository\FormulaireVacataireRepository;
use App\Repository\RemarqueRepository;
use App\Repository\LogEnseignantRepository;
use App\Repository\EnseignantRepository;
use App\Form\FormulaireTitulaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class MyDispoController extends AbstractController
{

  /**
  * @Route("/saisie-contrainte/{token}", name="saisieContrainte")
  */
  public function index(EnseignantRepository $enseignantRepository, FormulaireTitulaireRepository $formulaireTitulaireRepository ,$token,Request $request)
  {

    // Récupérer l'objet enseignant ayant le token $token
    $enseignant = $enseignantRepository->findByToken($token);
    $formulaireTitulaire = $formulaireTitulaireRepository->findAll();

    // Récupérer les données déjà enregistrées
    $remarquesSaisies = $enseignant[0]->getRemarques();
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
    /*  [
          'enseignant' => $enseignant,
          'form' => $form->createView(),
      ]*/
    );

    }
    // Afficher la page du formulaire de saisie
    return $this->render('my_dispo/formulaireTitulaire.html.twig', [
        'formulaireTitulaire' => $formulaireTitulaire[0],
        'form' => $form->createView(),
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
  * @Route("/evenements", name="evenements")
  */
  public function evenements(CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository, FormulaireVacataireRepository $formVacataireRepository)
  {
    $myarray = array();
    $events = $creneauRepository->selectStartEndTitleByType("evenements");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->start=$event["start"]->format("Y-m-d H:i:s");
      $object->end=$event["end"]->format("Y-m-d H:i:s");
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
  public function ChangementAnnee()
  {
    $entityManager = $this->getDoctrine()->getManager();

    $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);
    $enseignants = $repositoryEnseignant->findAll();

    $repositoryCreneau = $this->getDoctrine()->getRepository(Creneau::class);
    $creneaux = $repositoryCreneau->findAll();

    $repositoryRemarque = $this->getDoctrine()->getRepository(Remarque::class);
    $remarques = $repositoryRemarque->findAll();

    $repositoryLogEnseignant = $this->getDoctrine()->getRepository(LogEnseignant::class);
    $logsEnseignants = $repositoryLogEnseignant->findAll();

    foreach ($enseignants as $enseignantCourant) {
      $enseignantCourant->setSaisieFaite(false);
      $enseignantCourant->setDateSaisie(null);
      $enseignantCourant->setPremierMailRecu(false);
      $enseignantCourant->setDatePremierMail(null);
      $enseignantCourant->setMailRelanceRecu(false);
      $enseignantCourant->setDateDerniereRelance(null);
      $enseignantCourant->setDateDerniereModif(null);
      $enseignantCourant->setNbRelance(0);
      $entityManager->persist($enseignantCourant);

      $tabRemarques = $enseignantCourant->getRemarques();
      foreach ($tabRemarques as $remarque) {
        if($remarque->getType() == "Ponctuelle"){
          $entityManager->remove($remarque);
        }
      }
      $entityManager->flush();

      $tabCreneaux = $enseignantCourant->getCreneaux();
      foreach ($tabCreneaux as $creneau) {
        if($creneau->getType() == "Evenement"){
        $entityManager->remove($creneau);
      }
      }
      $entityManager->flush();

      $tabLogsEnseignants = $enseignantCourant->getLogsEnseignant();
      foreach ($tabLogsEnseignants as $logEnseignant) {
        $entityManager->remove($logEnseignant);
      }
      $entityManager->flush();
    }


    return $this->render('my_dispo/confirmationChangementAnnee.html.twig');
  }

}
