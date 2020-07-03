<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Entity\LogEnseignant;
use App\Repository\EnseignantRepository;
use App\Repository\LogEnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \ZipArchive;
use Symfony\Component\HttpFoundation\Session\Session;
use \Swift_Mailer;
use \Swift_SmtpTransport;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class EnseignantController extends AbstractController
{

  /**
  * @Route("/enseignant/MAJ/regroupementEnseignements", name="changer_regroupement" , methods={"POST"})
  */
  public function MAJRegroupement(EnseignantRepository $enseignantRepository)
  {

    // Cette méthode doit être accessible pouur les enseignants et ne doit pas être protégée par le pare-feu

    $entityManager = $this->getDoctrine()->getManager();

    $idEnseignant = $_POST["idEnseignant"];
    $valeur = $_POST['reponse'];

    $enseignant = $enseignantRepository->findById($idEnseignant)[0];
    $enseignant->setGrouperEnseignements($valeur);
    $entityManager->persist($enseignant);
    $entityManager->flush();

    return new Response();
  }



  /**
   * @Route("/admin/enseignant/GenerationToken", name="random_token")
  */
  public function GenererToken()
      {

        // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu

        $entityManager = $this->getDoctrine()->getManager();
        $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);
        $enseignants = $repositoryEnseignant->findAll();

        $listeCharacteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$_.+!*()';
        $tokenLength = 90;

        foreach ($enseignants as $enseignantCourant) {
          $randomString = "";
            for ($i = 0; $i < $tokenLength; $i++) {
                $randomString .= $listeCharacteres[rand(0, strlen($listeCharacteres) - 1)];
            }
          $randomString .= $enseignantCourant->getId();
          $enseignantCourant->setToken($randomString);
          $entityManager->persist($enseignantCourant);
          $entityManager->flush();
        }

        return $this->render('enseignant/confirmationToken.html.twig');

      }

      /**
       * @Route("/admin/enseignant/GenerationUnToken/{id}", name="random_Onetoken")
      */
      public function GenererUnToken(Enseignant $enseignant)
          {
            // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu

            $entityManager = $this->getDoctrine()->getManager();

            $listeCharacteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$_.+!*()';
            $tokenLength = 90;

              $randomString = "";
                for ($i = 0; $i < $tokenLength; $i++) {
                    $randomString .= $listeCharacteres[rand(0, strlen($listeCharacteres) - 1)];
                }
              $randomString .= $enseignant->getId();
              $enseignant->setToken($randomString);
              $entityManager->persist($enseignant);
              $entityManager->flush();


              return $this->render('enseignant/show.html.twig', [
                  'enseignant' => $enseignant,
              ]);

          }

      /**
       * @Route("/admin/enseignant/telechargerContrainte", name="telechargerContrainte")
       */
      public function telechargerContrainte()
      {
        // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu

        $zip = new \ZipArchive();

        //Supprime l'archive précédente si existante
        if (file_exists ('./Exportation-Contraintes/Contraintes.zip')) {
            unlink('./Exportation-Contraintes/Contraintes.zip');
        }


        // Fonction pour le formatage des noms des enseignants
        function enleverCaracteresSpeciaux($text) {
            $utf8 = array(
              '/[áàâãªä]/u' => 'a',
              '/[ÁÀÂÃÄ]/u' => 'A',
              '/[ÍÌÎÏ]/u' => 'I',
              '/[íìîï]/u' => 'i',
              '/[éèêë]/u' => 'e',
              '/[ÉÈÊË]/u' => 'E',
              '/[óòôõºö]/u' => 'o',
              '/[ÓÒÔÕÖ]/u' => 'O',
              '/[úùûü]/u' => 'u',
              '/[ÚÙÛÜ]/u' => 'U',
              '/ç/' => 'c',
              '/Ç/' => 'C',
              '/ñ/' => 'n',
              '/Ñ/' => 'N',

            );
            return preg_replace(array_keys($utf8), array_values($utf8), $text);
          }

        // Récupérer tous les enseignants
              $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);
              $enseignants = $repositoryEnseignant->findAll();

       // Initialisation ZipArchive
              $zip = new \ZipArchive();
              $filename = './Exportation-Contraintes/Contraintes.zip';

      // Créer un fichier pour chaque enseignant qu'on ajoute à l'archive

                foreach ( $enseignants as $enseignantCourant) {

                  // Initialisation  des variables
                  $nomEnseignant = enleverCaracteresSpeciaux($enseignantCourant->getNom());
                  $prenomEnseignant = enleverCaracteresSpeciaux($enseignantCourant->getPrenom());
                  $idEnseignant = $enseignantCourant->getId();
                  $creneaux = $enseignantCourant->getCreneaux();
                  $remarques = $enseignantCourant->getRemarques();

                  // Initialisation du nom du fichier
                  $nomFichierCourant = $nomEnseignant.$prenomEnseignant.$idEnseignant.'.csv';

                  // Ajout au fichier les infos de l'enseignant
                  $texte = $nomEnseignant.', '.$enseignantCourant->getPrenom().', '.$enseignantCourant->getMail().', '.$enseignantCourant->getStatut().', '.$enseignantCourant->getGrouperEnseignements()."\r";
                  file_put_contents( $nomFichierCourant, $texte);

                  // Ajout au fichier les remarques de l'enseignant
                  foreach ( $remarques as $remarqueCourant ) {
                    $type = $remarqueCourant->getType();
                    $contenu = $remarqueCourant->getContenu();
                    $texte = file_get_contents($nomFichierCourant);
                    $texte .= $type.', '.$contenu."\r";
                    file_put_contents( $nomFichierCourant, $texte);
                  }


                  // Ajout au fichier les créneaux de l'enseignant
                  foreach ( $creneaux as $creneauCourant ) {
                    // Formatage des dates
                    $dateDebutDate = $creneauCourant->getDateDebut();
                    $dateDebutString = $dateDebutDate->format('H-i d-m-Y');
                    $dateFinDate = $creneauCourant->getDateFin();
                    $dateFinString = $dateFinDate->format('H-i d-m-Y');

                    $texte = file_get_contents($nomFichierCourant);
                    $texte .= $creneauCourant->getTitre().', '.$creneauCourant->getPrioOuPref().', '.$dateDebutString.', '.$dateFinString.', '.$creneauCourant->getType()."\r";
                    file_put_contents( $nomFichierCourant, $texte);
                  }

                  // Vérifier si l'archive peut être ouverte + création de l'archive
                  if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
                      exit("cannot open <$filename><br/>");
                  }
                  else{
                    // Ajouter le fichier courant à l'archive
                    $zip->addFile($nomFichierCourant);
                  }

                }
                // Ajouter le fichier explicatif "Lisez-moi.txt"
                $zip->addFile('./Exportation-Contraintes/Lisez-moi.txt');
                // Fermer l'archive
                $zip->close();

                // Supprimer tous les fichiers à l'extérieur de l'archive
                foreach ( $enseignants as $enseignantCourant) {
                  unlink(enleverCaracteresSpeciaux($enseignantCourant->getNom()).enleverCaracteresSpeciaux($enseignantCourant->getPrenom()).$enseignantCourant->getId().'.csv');
                }

            return $this->render('enseignant/confirmationTelechargement.html.twig');

    }





      /**
       * @Route("/admin/enseignant/confirmationEnvoieMail/", name="envoie_mail", methods={"GET"})
       */
      public function notifierEnseignant()
      {
        // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu


            $session = new Session();
            $modeleMail = $session->get('modeleMail');
            $enseignants = $session->get('enseignants');


              $sujet =$modeleMail->getSujet();

              $entityManager = $this->getDoctrine()->getManager();

              foreach ( $enseignants  as  $enseignantCourant ){
                $contenu = $modeleMail->getContenu();
                $nomEnseignant = $enseignantCourant->getNom();
                $prenomEnseignant = $enseignantCourant->getPrenom();
                $urlEnseignant = $this->generateUrl('saisieContrainte',['token'=> $enseignantCourant->getToken()],false);

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
                $contenu = $partieAvantLien." ".$urlEnseignant."\r\r\r".$partieAprèsLien;
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



              $transport = (new \Swift_SmtpTransport($_ENV['ADRESS_SERVER_SMTP'], 465))
                ->setEncryption('ssl')
                ->setAuthMode('login')
                ->setUsername($_ENV['LOGIN_SMTP'])
                ->setPassword($_ENV['PASSWORD_SMTP']);
            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message($sujet))
               ->setFrom($_ENV['MAIL_SENDER'])
               ->setTo($enseignantCourant->getMail())
               ->setReplyTo($_ENV['MAIL_SENDER'])
               ->setBody($contenuFinal);
            $mailer->send($message);

          }
          $formations = $session->get('formations');
          $saisieFaite = $session->get('saisieFaite');
          $mailRelanceRecu = $session->get('mailRelanceRecu');
          $statut = $session->get('statut');
          session_destroy();
          $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);
          $enseignants = $repositoryEnseignant->findByGeneral($tab = array('saisieFaite' => $saisieFaite ,'statut' => $statut, 'formations' => $formations, 'mailRelanceRecu' => $mailRelanceRecu ));

          foreach ( $enseignants  as  $enseignantCourant ){
          // Premier mail
          if($sujet == 'Saisie de vos contraintes et disponibilités IUT Anglet'){
            $enseignantCourant->setPremierMailRecu(true);
            $date1 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $date1->setTimezone(new \DateTimeZone('UTC'));
            $enseignantCourant->setDatePremierMail($date1);
            $entityManager->persist($enseignantCourant);
          }
          else{
            // Mail relance
            if($sujet == 'Relance pour la saisie de vos contraintes et disponibilités IUT Anglet'){
              $enseignantCourant->setMailRelanceRecu(true);
              $date2 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
              $date2->setTimezone(new \DateTimeZone('UTC'));
              $enseignantCourant->setDateDerniereRelance($date2);
              $enseignantCourant->setNbRelance($enseignantCourant->getNbRelance() +1);
              $entityManager->persist($enseignantCourant);
            }
          }
        }
        $entityManager->flush();





          return $this->render('modele_mail/confirmationEnvoieMail.html.twig');
    }


    /**
     * @Route("/admin/enseignant/", name="enseignant_index", methods={"GET"})
     */
    public function index(EnseignantRepository $enseignantRepository): Response
    {
      // Cette méthode du CRUD est utilisée par l'admin, on la protège derrière le pare-feu

        return $this->render('enseignant/index.html.twig', [
            'enseignants' => $enseignantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/enseignant/index", name="enseignant_indexadmin", methods={"GET"})
     */
    public function indexAdmin(EnseignantRepository $enseignantRepository, LogEnseignantRepository $logEnseignantRepository): Response
    {
      // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu


        return $this->render('enseignant/acceuiladmin.html.twig', [
            'enseignants' => $enseignantRepository->findByEnSommeil(false),
            'logsEnseignants' => $logEnseignantRepository->findOrderByDate(),

        ]);
    }

    /**
     * @Route("/admin/enseignant/new", name="enseignant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
      // Cette méthode du CRUD est utilisée par l'admin, on la protège derrière le pare-feu

        $enseignant = new Enseignant();
        $enseignant->setSaisieFaite(false);
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          $formations=$enseignant->getFormations();
          foreach ($formations as $formation) {
            $formation->addEnseignant($enseignant);
          }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enseignant);
            $entityManager->flush();

            return $this->redirectToRoute('enseignant_index');
        }

        return $this->render('enseignant/new.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/enseignant/{id}", name="enseignant_show", methods={"GET"})
     */
    public function show(Enseignant $enseignant): Response
    {
      // Cette méthode du CRUD est utilisée par l'admin, on la protège derrière le pare-feu

        return $this->render('enseignant/show.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }



    /**
     * @Route("/admin/enseignant/edit/{id}", name="enseignant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Enseignant $enseignant): Response
    {
      // Cette méthode du CRUD est utilisée par l'admin, on la protège derrière le pare-feu

        $form = $this->createForm(EnseignantType::class, $enseignant);
        // Modifier la saisie faite que lorsqu'on edit un enseignant
        $form->add('saisieFaite', ChoiceType::class, array(
                              'choices'  => array(
                                  'Non' => false,
                                  'Oui' => true
                          ),
                          'help' => 'Attention : en passant le champ "Saisie faite" à non, les créneaux, remarques et logs déjà en BD ne sont pas effacés.'
                        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $formations=$enseignant->getFormations();
          foreach ($formations as $formation) {
            $formation->addEnseignant($enseignant);
          }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('enseignant_index');
        }

        return $this->render('enseignant/edit.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/enseignant/delete/{id}", name="enseignant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Enseignant $enseignant): Response
    {
      // Cette méthode du CRUD est utilisée par l'admin, on la protège derrière le pare-feu

        if ($this->isCsrfTokenValid('delete'.$enseignant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($enseignant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('enseignant_index');
    }

    /**
     * @Route("/admin/enseignant/supprimer/{id}", name="enseignant_supprimer")
     */
    public function supprimer(Enseignant $enseignant){
      // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu

        return $this->render('enseignant/delete.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }

    /**
     * @Route("/admin/enseignant/confirmationToken/{id}", name="token_confirmation")
     */
    public function confirmationToken(Enseignant $enseignant, EnseignantRepository $enseignantRepository){

      // Cette méthode est utilisée par l'admin, on la protège derrière le pare-feu

        return $this->render('enseignant/token.html.twig', [
            'enseignant' => $enseignant,
            'enseignants' => $enseignantRepository->findAll(),

        ]);
    }






}
