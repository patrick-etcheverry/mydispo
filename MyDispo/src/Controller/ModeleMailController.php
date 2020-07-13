<?php

namespace App\Controller;

use App\Entity\ModeleMail;
use App\Entity\Formation;
use App\Entity\Enseignant;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\IntegerType;
use App\Form\ModeleMailType;
use App\Repository\ModeleMailRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Persistence\ObjectManager;
/**
* @Route("/modele/mail")
*/
class ModeleMailController extends AbstractController
{
  /**
  * @Route("/admin/", name="modele_mail_index", methods={"GET"})
  */
  public function index(ModeleMailRepository $modeleMailRepository): Response
  {
    return $this->render('modele_mail/index.html.twig', [
      'modele_mails' => $modeleMailRepository->findAll(),
    ]);
  }

  /**
  * @Route("/admin/new", name="modele_mail_new", methods={"GET","POST"})
  */
  public function new(Request $request): Response
  {
    $modeleMail = new ModeleMail();
    $form = $this->createForm(ModeleMailType::class, $modeleMail);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($modeleMail);
      $entityManager->flush();

      return $this->redirectToRoute('modele_mail_index');
    }

    return $this->render('modele_mail/new.html.twig', [
      'modele_mail' => $modeleMail,
      'form' => $form->createView(),
    ]);
  }

  /**
  * @Route("/admin/envoieMail", name="envoie_modele_mail", methods={"GET","POST"})
  */
  public function formEnvoieMail(Request $request, FormationRepository $formationRepository)
  {
    $defaultData = ['message' => 'Type your message here'];

    $tabListeFormations = array();
    $toutesLesFormations = $formationRepository->findAll();
    foreach ($toutesLesFormations as $formationCourante)
    {
      $tabListeFormations[$formationCourante->getNomCourt()] = $formationCourante->getNomCourt();
    }



    $form = $this->createFormBuilder($defaultData)
    ->add('nom', EntityType::class, array(
      'class' => ModeleMail::class,
      'choice_label' => 'nom',
      'label' => 'Type de mail :',
      'multiple' => false,
      'expanded' => false,
      'required' => true
    ))

    ->add('nomCourt', ChoiceType::class, array(
      'help' => "Attention : Si aucune formation n'est sélectionnée, aucun enseignant ne sera ciblé par le mail.",
      'label' => 'Enseignant intervenant en :',
      'choices' => $tabListeFormations,
      'data' => $toutesLesFormations,
      'multiple' => true,
      'expanded' => true,
      'required' => true
    ))
    ->add('statut', ChoiceType::class, array(
      'choices' => [
        'Titulaire' => 'Titulaire',
        'Vacataire' => 'Vacataire',
        'Tous les statuts' => 'Tous les statuts',
        ],
        'data' => 'Tous les statuts',
        'label' => 'Enseignant ayant le statut de :',
        'multiple' => false,
        'expanded' => true,
        'required' => true
      ))
      ->add('saisieFaite', ChoiceType::class, array(
        'choices' => [
          'Une saisie effectuée' => 'true',
          'Une saisie non effectuée' => 'false',
          'Toutes les saisies' => 'Toutes les saisies',
          ],
          'data' => 'Toutes les saisies',
          'label' => 'Enseignant avec : ',
          'multiple' => false,
          'expanded' => true,
          'required' => true
        ))
        ->add('mailRelanceRecu', ChoiceType::class, array(
          'choices' => [
            'Ayant reçu un mail de relance' => 'true',
            'N\'ayant pas reçu de mail de relance' => 'false',
            'Toutes les relances' => 'Toutes les relances',
            ],
            'data' => 'Toutes les relances',
            'label' => 'Enseignant : ',
            'multiple' => false,
            'expanded' => true,
            'required' => true
          ))
          ->getForm();

          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $repositoryModeleMail = $this->getDoctrine()->getRepository(ModeleMail::class);
            $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);

            $nom = $form["nom"]->getData()->getNom();
            $formations = $form["nomCourt"]->getData();
            $statut = $form["statut"]->getData();
            $saisieFaite = $form["saisieFaite"]->getData();
            $mailRelanceRecu = $form["mailRelanceRecu"]->getData();
            $modeleMail = $repositoryModeleMail->findOneByNomModeleMail($nom);

            $enseignants = $repositoryEnseignant->findByGeneral($tab = array('saisieFaite' => $saisieFaite ,'statut' => $statut, 'formations' => $formations, 'mailRelanceRecu' => $mailRelanceRecu ));

            $enseignantsActif = array();
            foreach ($enseignants as $enseignantCourant) {
              if($enseignantCourant->getEnSommeil() != true){
                array_push($enseignantsActif,$enseignantCourant);
              }
            }

            $session = new Session();
            $session->set('enseignants',$enseignantsActif);
            $session->set('modeleMail',$modeleMail);
            $session->set('formations',$formations);
            $session->set('saisieFaite',$saisieFaite);
            $session->set('mailRelanceRecu',$mailRelanceRecu);
            $session->set('statut',$statut);

            if($form->get('nomCourt')->getData() == null){
              return $this->render('modele_mail/envoieMail.html.twig', [
                'form' => $form->createView(),
              ]);
            }

            return $this->render('modele_mail/envoieMailResume.html.twig', [
              'data' => $data,
              'modeleMail' => $modeleMail,
              'enseignants' => $enseignantsActif,
              'nomModeleMail' => $data['nom'],
              'tabMailRelanceRecu' => $form->get('mailRelanceRecu')->getData(),
              'tabFormation' => $form->get('nomCourt')->getData(),
              'tabStatut' => $form->get('statut')->getData(),
              'tabSaisie' => $form->get('saisieFaite')->getData(),
            ]);

          }
          return $this->render('modele_mail/envoieMail.html.twig', [
            'form' => $form->createView(),
          ]);
        }




        /**
        * @Route("/admin/premierMail/{id}/{compteur}", name="notifierUnEnseignant", methods={"GET"})
        */
        public function notifierUnEnseignant(Enseignant $enseignant , int $compteur)
        {

          switch ($compteur)
          {
            case 1 :
            $nom = 'Mail premier contact';
            $enseignant->setPremierMailRecu(true);
            $date1 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $enseignant->setDatePremierMail($date1);
            break;
            case 2 :
            $nom = 'Mail de relance';
            $enseignant->setMailRelanceRecu(true);
            $date2 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $enseignant->setDateDerniereRelance($date2);
            $enseignant->setNbRelance($enseignant->getNbRelance() +1);

            break;
            case 3 :
            $nom = 'Mail oubli';
            break;
          }
          $repositoryModeleMail = $this->getDoctrine()->getRepository(ModeleMail::class);
          $modeleMail = $repositoryModeleMail->findOneByNomModeleMail($nom);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($enseignant);
          $entityManager->flush();

          $contenu = $modeleMail->getContenu();
          $nomEnseignant = $enseignant->getNom();
          $prenomEnseignant = $enseignant->getPrenom();
          $urlEnseignant = $this->generateUrl('saisieContrainte',['token'=> $enseignant->getToken()],false);

          //Remplace [PRENOM] par le prenom de l'enseignant
          if(stristr($contenu, "[*PRENOM*]", true) == TRUE) {
            $partieAvantLien = stristr($contenu, "[*PRENOM*]", true);
            $partieAprèsLien = stristr($contenu, "[*PRENOM*]");
            $contenu = $partieAvantLien." ".$prenomEnseignant." ".$partieAprèsLien;
            $contenuFinal = str_replace("[*PRENOM*]", "", $contenu);
          }
          else{
            $contenuFinal = $contenu;
          }

          //Remplace [LIEN] par le lien personnalisé de l'enseignant
          if(stristr($contenuFinal, "[*LIEN*]", true) == TRUE) {
            $partieAvantLien = stristr($contenuFinal, "[*LIEN*]", true);
            $partieAprèsLien = stristr($contenuFinal, "[*LIEN*]");
            $contenu = $partieAvantLien." ".$urlEnseignant. PHP_EOL .PHP_EOL . PHP_EOL .$partieAprèsLien;
            $contenuFinal = str_replace("[*LIEN*]", "", $contenu);
          }
          else {
            if(isset($contenuFinal) == FALSE){
              $contenuFinal = $contenu;
            }
          }

          //Remplace [NOM] par le nom de l'enseignant
          if(stristr($contenuFinal, "[*NOM*]", true) == TRUE) {
            $partieAvantLien = stristr($contenuFinal, "[*NOM*]", true);
            $partieAprèsLien = stristr($contenuFinal, "[*NOM*]");
            $contenu = $partieAvantLien." ".$nomEnseignant." ".$partieAprèsLien;
            $contenuFinal = str_replace("[*NOM*]", "", $contenu);
          }

          $transport = (new \Swift_SmtpTransport($_ENV['ADRESS_SERVER_SMTP'],  $_ENV['SERVER_SMTP_PORT']))
          ->setEncryption($_ENV['ENCRYPTION_SERVER_SMTP'])
          ->setAuthMode('login')
          ->setUsername($_ENV['LOGIN_SMTP'])
          ->setPassword($_ENV['PASSWORD_SMTP']);
          $mailer = new \Swift_Mailer($transport);
          $message = (new \Swift_Message($modeleMail->getSujet()))
          ->setFrom($_ENV['MAIL_SENDER'])
          ->setTo($enseignant->getMail())
          ->setReplyTo($_ENV['MAIL_SENDER'])
          ->setBody($contenuFinal);
          $mailer->send($message);

          return $this->render('modele_mail/confirmationEnvoieMail.html.twig');
        }


        /**
        * @Route("/mailResume/{id}", name="mailResume", methods={"GET"})
        */
        public function mailResume(Enseignant $enseignant)
        {
          $titrecontrainte = "";
          $creneaux = $enseignant->getCreneaux();
          $remarques = $enseignant->getRemarques();
          $sujetMail = "Contraintes emploi du temps IUT de Bayonne - Résumé";
          $contenu = "Bonjour," . PHP_EOL . PHP_EOL . "Vous trouverez ci-dessous le résumé de votre saisie : " . PHP_EOL .PHP_EOL .PHP_EOL;


          /*************************************************
          RESUMER LES CONTRAINTES / DISPONIBILITES HEBDOMADAIRES SAISIES
          ***************************************************/
          if ($enseignant->getStatut() == 'Vacataire')
          {
            $contenu .= "DISPONIBILITES HEBDOMADAIRES : " . PHP_EOL . PHP_EOL;
          }
          else
          {
            $contenu .= "CONTRAINTES HEBDOMADAIRES : " . PHP_EOL . PHP_EOL;
          }

          $contenuContrainteHebdo = "";
          $auMoinsUneContrainteHebdoSaisie = false;
          $tabTraductionJourSemaineAnglaisVersFrancais = ['Monday'=> 'lundi', 'Tuesday'=>'mardi',
          'Wednesday' => 'mercredi', 'Thursday' => 'jeudi',
          'Friday' => 'vendredi', 'Saturday'=>'samedi',
          'Sunday' => 'dimanche'];
          foreach ($creneaux as $creneauxCourant)
          {
            switch ($creneauxCourant->getType())
            {
              case "ContraintePro":
              $auMoinsUneContrainteHebdoSaisie = true;
              $titrecontrainte = trim($creneauxCourant->getTitre());
              $contenuContrainteHebdo .= $titrecontrainte .PHP_EOL;
              $jourSemaineEnAnglais = $creneauxCourant->getDateDebut()->format('l');
              $jourSemaineEnFrancais = $tabTraductionJourSemaineAnglaisVersFrancais[$jourSemaineEnAnglais];
              $contenuContrainteHebdo .= $jourSemaineEnFrancais." ";
              $contenuContrainteHebdo .= "de ".$creneauxCourant->getDateDebut()->format('H:i');
              $contenuContrainteHebdo .= " à ".$creneauxCourant->getDateFin()->format('H:i'). PHP_EOL;
              $contenuContrainteHebdo .= "Priorité : ".$creneauxCourant->getPrioOuPref().PHP_EOL;
              $contenuContrainteHebdo .= "Type : contrainte professionnelle" . PHP_EOL. PHP_EOL;
              break;

              case "ContraintePerso":
              $auMoinsUneContrainteHebdoSaisie = true;
              $titrecontrainte = trim($creneauxCourant->getTitre());
              if ($titrecontrainte == "")
              {
                $titrecontrainte = "Contrainte personnelle ";
              }
              $contenuContrainteHebdo .= $titrecontrainte .PHP_EOL;
              $jourSemaineEnAnglais = $creneauxCourant->getDateDebut()->format('l');
              $jourSemaineEnFrancais = $tabTraductionJourSemaineAnglaisVersFrancais[$jourSemaineEnAnglais];
              $contenuContrainteHebdo .= $jourSemaineEnFrancais." ";
              $contenuContrainteHebdo .= "de ".$creneauxCourant->getDateDebut()->format('H:i');
              $contenuContrainteHebdo .= " à ".$creneauxCourant->getDateFin()->format('H:i'). PHP_EOL;
              $contenuContrainteHebdo .= "Priorité : ".$creneauxCourant->getPrioOuPref().PHP_EOL;
              $contenuContrainteHebdo .= "Type : contrainte personnelle". PHP_EOL. PHP_EOL;
              break;


              case "Disponibilite":
              $auMoinsUneContrainteHebdoSaisie = true;
              $jourSemaineEnAnglais = $creneauxCourant->getDateDebut()->format('l');
              $jourSemaineEnFrancais = $tabTraductionJourSemaineAnglaisVersFrancais[$jourSemaineEnAnglais];
              $contenuContrainteHebdo .= $jourSemaineEnFrancais." ";
              $contenuContrainteHebdo .= "de ".$creneauxCourant->getDateDebut()->format('H:i');
              $contenuContrainteHebdo .= " à ".$creneauxCourant->getDateFin()->format('H:i'). PHP_EOL;
              $contenuContrainteHebdo .= "Priorité : ".$creneauxCourant->getPrioOuPref().PHP_EOL.PHP_EOL;
              break;
            }
          }
          // Vérifier que l'enseignant a saisi au moins une contrainte hebdomadaire
          if ($auMoinsUneContrainteHebdoSaisie)
          {
            $contenu .= $contenuContrainteHebdo. PHP_EOL. PHP_EOL;
          }
          else
          {
            $contenu .= "Aucune contrainte saisie". PHP_EOL. PHP_EOL;
          }

          /**********************************************************
          RESUMER LES REMARQUES SUR LES CONTRAINTES HEBDOMADAIRES
          **********************************************************/
          if ($enseignant->getStatut() == 'Vacataire')
          {
            $contenu .= PHP_EOL. "REMARQUES CONCERNANT VOS DISPONIBILITES HEBDOMADAIRES : " . PHP_EOL . PHP_EOL;
          }
          else
          {
            $contenu .= PHP_EOL. "REMARQUES CONCERNANT VOS CONTRAINTES HEBDOMADAIRES : " . PHP_EOL . PHP_EOL;
          }

          $contenuRemarquesHebdo = "";
          $auMoinsUneRemarqueSurContrainteHebdoSaisie = false;
          foreach ($remarques as $remarquesCourant)
          {
            if($remarquesCourant->getType() == "Hebdomadaire")
            {
              if (strlen(trim($remarquesCourant->getContenu())) > 0)
              {
                $auMoinsUneRemarqueSurContrainteHebdoSaisie = true;
                $contenuRemarquesHebdo .= trim($remarquesCourant->getContenu()).". " .PHP_EOL .PHP_EOL;
              }
            }
          }
          // Vérifier que l'enseignant a saisi au moins une remarque sur les contraintes hebdomadaire
          if ($auMoinsUneRemarqueSurContrainteHebdoSaisie)
          {
            $contenu .= $contenuRemarquesHebdo. PHP_EOL. PHP_EOL;
          }
          else
          {
            $contenu .= "Aucune remarque.". PHP_EOL. PHP_EOL;
          }


          /*************************************************
          RESUMER LES CONTRAINTES PONCTUELLES SAISIES
          ***************************************************/
          $contenu .=  PHP_EOL . "CONTRAINTES PONCTUELLES : " . PHP_EOL . PHP_EOL;
          $contenuContraintesPonctu = "";
          $auMoinsUneContraintePonctuSaisie = false;
          foreach ($creneaux as $creneauxCourant)
          {
            if($creneauxCourant->getType() == "ContrainteProPonctu")
            {
              $auMoinsUneContraintePonctuSaisie = true;
              $contenuContraintesPonctu .= "- Titre : ".trim($creneauxCourant->getTitre()).", Date : "
              .$creneauxCourant->getDateDebut()->format('d-m-Y').". " . PHP_EOL . PHP_EOL;
            }
          }
          // Vérifier que l'enseignant a saisi au moins une remarque sur les contraintes ponctuelles
          if ($auMoinsUneContraintePonctuSaisie)
          {
            $contenu .= $contenuContraintesPonctu. PHP_EOL. PHP_EOL;
          }
          else
          {
            $contenu .= "Aucune contrainte saisie.". PHP_EOL. PHP_EOL;
          }



          /**********************************************************
          RESUMER LES REMARQUES SUR LES CONTRAINTES PONCTUELLES
          **********************************************************/
          $contenu .= PHP_EOL . PHP_EOL ."REMARQUES CONCERNANT VOS CONTRAINTES PONCTUELLES : " . PHP_EOL . PHP_EOL;
          $contenuRemarquesPonctu = "";
          $auMoinsUneRemarqueSurContraintePonctuSaisie = false;
          foreach ($remarques as $remarquesCourant)
          {
            if($remarquesCourant->getType() == "Ponctuelle")
            {

              if (strlen(trim($remarquesCourant->getContenu())) > 0)
              {
                $auMoinsUneRemarqueSurContraintePonctuSaisie = true;
                $contenu .= trim($remarquesCourant->getContenu()).". " .PHP_EOL .PHP_EOL;
              }
            }
          }
          // Vérifier que l'enseignant a saisi au moins une remarque sur les contraintes ponctuelles
          if ($auMoinsUneRemarqueSurContraintePonctuSaisie)
          {
            $contenu .= $contenuRemarquesPonctu. PHP_EOL. PHP_EOL;
          }
          else
          {
            $contenu .= "Aucune remarque.". PHP_EOL. PHP_EOL;
          }


          /**********************************************************
          RESUMER LA PREFERENCE CONCERNANT LE GROUPEMENT DES ENSEIGNEMENTS
          **********************************************************/
          $contenu .= PHP_EOL . PHP_EOL ."GROUPEMENT DES ENSEIGNEMENTS : " . PHP_EOL . PHP_EOL;
          switch ($enseignant->getGrouperEnseignements())
          {
            case 'Regroupes':
            $contenu .= "Vous préférez regrouper vos enseignements.". PHP_EOL .PHP_EOL .PHP_EOL;
            break;

            case 'Non regroupes':
            $contenu .= "Vous préférez étaler vos enseignements.". PHP_EOL .PHP_EOL .PHP_EOL;
            break;

            default:
            $contenu .= "Vous n'avez indiqué aucune préférence.". PHP_EOL .PHP_EOL .PHP_EOL;
            break;
          }


          /**********************************************************
          Terminer et signer le mail
          **********************************************************/
          $contenu .= "Cordialement, " . PHP_EOL .$_ENV['ADMIN_NAME'];



          $transport = (new \Swift_SmtpTransport($_ENV['ADRESS_SERVER_SMTP'], $_ENV['SERVER_SMTP_PORT']))
          ->setEncryption($_ENV['ENCRYPTION_SERVER_SMTP'])
          ->setAuthMode('login')
          ->setUsername($_ENV['LOGIN_SMTP'])
          ->setPassword($_ENV['PASSWORD_SMTP']);
          $mailer = new \Swift_Mailer($transport);
          $message = (new \Swift_Message($sujetMail))
          ->setFrom($_ENV['MAIL_SENDER'])
          ->setTo($enseignant->getMail())
          ->setReplyTo($_ENV['MAIL_SENDER'])
          ->setBody($contenu);
          $mailer->send($message);

          return $this->render('my_dispo/confirmationEnvoieMail.html.twig', [
            'enseignant' => $enseignant,
          ]);

        }



        /**
        * @Route("/admin/{id}", name="modele_mail_show", methods={"GET"})
        */
        public function show(ModeleMail $modeleMail): Response
        {
          return $this->render('modele_mail/show.html.twig', [
            'modele_mail' => $modeleMail,
          ]);
        }

        /**
        * @Route("/admin/{id}/edit", name="modele_mail_edit", methods={"GET","POST"})
        */
        public function edit(Request $request, ModeleMail $modeleMail): Response
        {
          $form = $this->createForm(ModeleMailType::class, $modeleMail);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('modele_mail_index');
          }

          return $this->render('modele_mail/edit.html.twig', [
            'modele_mail' => $modeleMail,
            'form' => $form->createView(),
          ]);
        }

        /**
        * @Route("/admin/{id}", name="modele_mail_delete", methods={"DELETE"})
        */
        public function delete(Request $request, ModeleMail $modeleMail): Response
        {
          if ($this->isCsrfTokenValid('delete'.$modeleMail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($modeleMail);
            $entityManager->flush();
          }

          return $this->redirectToRoute('modele_mail_index');
        }

        /**
        * @Route("/admin/supprimer/{id}", name="modele_mail_supprimer")
        */
        public function supprimer(ModeleMail $modeleMail){
          return $this->render('modele_mail/delete.html.twig', [
            'modele_mail' => $modeleMail,
          ]);
        }


      }
