<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use \Swift_Mailer;
use \Swift_SmtpTransport;
/**
 * @Route("/enseignant")
 */
class EnseignantController extends AbstractController
{
  /**
   * @Route("/GenerationToken", name="random_token")
  */
  public function GenererToken()
      {
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);
        $enseignants = $repositoryEnseignant->findAll();

        $listeCharacteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($listeCharacteres);

        foreach ($enseignants as $enseignantCourant) {
          $randomString = $enseignantCourant->getId();
            for ($i = 0; $i < $charactersLength; $i++) {
                $randomString .= $listeCharacteres[rand(0, $charactersLength - 1)];
            }
          $enseignantCourant->setToken($randomString);
          $entityManager->persist($enseignantCourant);
          $entityManager->flush();
        }


        return $this->render('enseignant/confirmationToken.html.twig');

      }

      /**
       * @Route("/confirmationEnvoieMail/", name="envoie_mail", methods={"GET"})
       */
      public function notifierEnseignant()
      {

            $session = new Session();
            $modeleMail = $session->get('modeleMail');
            $enseignants = $session->get('enseignants');
            $sujet =$modeleMail->getSujet();
              $contenu = $modeleMail->getContenu();
              foreach ( $enseignants  as  $enseignantCourant ){
              $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465))
                ->setHost('smtp.gmail.com')
                ->setPort('465')
                ->setEncryption('ssl')
                ->setAuthMode('login')
                ->setUsername($_ENV['MAILER_USER'])
                ->setPassword($_ENV['MAILER_PASSWORD']);
            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message($sujet))
               ->setFrom('Patrick Etcheverry')
               ->setTo($enseignantCourant->getMail())
               ->setBody($contenu);
            $mailer->send($message);
/*
            // Premier mail
            if($sujet == 'Saisie de vos contraintes et disponibilités IUT Anglet'){
              $enseignantCourant->setPremierMailRecu(true);
              $date1 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
              $date1->setTimezone(new \DateTimeZone('UTC'));
              $enseignantCourant->setDatePremierMail($date1);
            }
            else{
              // Mail relance
              if($sujet == 'Relance pour la saisie de vos contraintes et disponibilités IUT Anglet'){
                $enseignantCourant->setMailRelanceRecu(true);
                $date2 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
                $date2->setTimezone(new \DateTimeZone('UTC'));
                $enseignantCourant->setDateDerniereRelance($date2);
                $enseignantCourant->setNbRelance($enseignantCourant->getNbRelance() +1);

              }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enseignantCourant);
            $entityManager->flush();
*/
          }
            return $this->render('modele_mail/confirmationEnvoieMail.html.twig');
        }

    /**
     * @Route("/", name="enseignant_index", methods={"GET"})
     */
    public function index(EnseignantRepository $enseignantRepository): Response
    {
        return $this->render('enseignant/index.html.twig', [
            'enseignants' => $enseignantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index", name="enseignant_indexadmin", methods={"GET"})
     */
    public function indexAdmin(EnseignantRepository $enseignantRepository): Response
    {
        return $this->render('enseignant/acceuiladmin.html.twig', [
            'enseignants' => $enseignantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="enseignant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $enseignant = new Enseignant();
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{id}", name="enseignant_show", methods={"GET"})
     */
    public function show(Enseignant $enseignant): Response
    {
        return $this->render('enseignant/show.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="enseignant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Enseignant $enseignant): Response
    {
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('enseignant_index');
        }

        return $this->render('enseignant/edit.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enseignant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Enseignant $enseignant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enseignant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($enseignant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('enseignant_index');
    }

    /**
     * @Route("/supprimer/{id}", name="enseignant_supprimer")
     */
    public function supprimer(Enseignant $enseignant){
        return $this->render('enseignant/delete.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }




}
