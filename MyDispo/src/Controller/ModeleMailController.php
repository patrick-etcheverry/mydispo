<?php

namespace App\Controller;

use App\Entity\ModeleMail;
use App\Entity\Formation;
use App\Entity\Enseignant;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ModeleMailType;
use App\Repository\ModeleMailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use \Swift_Mailer;
use \Swift_SmtpTransport;
/**
 * @Route("/modele/mail")
 */
class ModeleMailController extends AbstractController
{
    /**
     * @Route("/", name="modele_mail_index", methods={"GET"})
     */
    public function index(ModeleMailRepository $modeleMailRepository): Response
    {
        return $this->render('modele_mail/index.html.twig', [
            'modele_mails' => $modeleMailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="modele_mail_new", methods={"GET","POST"})
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
     * @Route("/envoieMail", name="envoie_modele_mail", methods={"GET","POST"})
     */
public function formEnvoieMail(Request $request)
{
    $defaultData = ['message' => 'Type your message here'];
    $form = $this->createFormBuilder($defaultData)
        ->add('nom', EntityType::class, array(
                'class' => ModeleMail::class,
                'choice_label' => 'nom',
                'label' => 'Type de mail',
                'multiple' => false,
                'expanded' => false
            ))
            ->add('nomCourt', EntityType::class, array(
                    'class' => Formation::class,
                    'choice_label' => 'nomCourt',
                    'label' => 'Formation ciblée',
                    'multiple' => true,
                    'expanded' => true
                ))
                ->add('statut', ChoiceType::class, array(
                        'choices' => [
                          'Titulaire' => 'Titulaire',
                          'Vacataire' => 'Vacataire',
                          ],
                        'label' => 'Type de statut ciblé',
                        'multiple' => true,
                        'expanded' => true
                    ))
                ->add('saisieFaite', ChoiceType::class, array(
                        'choices' => [
                          'Saisie effectuée' => true,
                          'Saisie non effectuée' => false,
                          ],
                        'label' => 'Type de saisie ciblée',
                        'multiple' => true,
                        'expanded' => true
                    ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // data is an array with "name", "email", and "message" keys
      $data = $form->getData();
      $repositoryModeleMail = $this->getDoctrine()->getRepository(ModeleMail::class);
      $repositoryEnseignant = $this->getDoctrine()->getRepository(Enseignant::class);

      $nom = $form["nom"]->getData()->getNom();
      $formations = $form["nomCourt"]->getData();
      $statut = $form["statut"]->getData();
      $saisieFaite = $form["saisieFaite"]->getData();


        $modeleMail = $repositoryModeleMail->findOneByNomModeleMail($nom);


        $compteur = 0;
        //si le nombre d'élément dans le tableau des formations est > 2
        if(sizeof($formations) > 1){
          $compteur = 1;
        }
          // si le nombre d'élément dans le tableau des statuts est > 2
        if (sizeof($statut) > 1){
          $compteur = $compteur + 2;
        }
        // si le nombre d'élément dans le tableau des saisies est > 2
        if (sizeof($saisieFaite) > 1){
            $compteur = $compteur + 4;
        }
        switch ($compteur) {
    case 0:
        // [OK] Recherche juste avec les saisies et statuts et formations
        $enseignants = $repositoryEnseignant->findBy0($tab = array('saisieFaite' => $saisieFaite ,'statut' => $statut, 'formations' => $formations[0]->getNomCourt() ));
        break;
    case 1:
      // [OK] Recherche juste avec les saisies et statuts
      $enseignants = $repositoryEnseignant->findBy1($tab = array('saisieFaite' => $saisieFaite ,'statut' => $statut ));
        break;
    case 2:
        // [OK] Recherche juste avec les saisies et formations
        $enseignants = $repositoryEnseignant->findBy2($tab = array('saisieFaite' => $saisieFaite ,'formations' => $formations[0]->getNomCourt() ));
        break;
    case 4:
        // [OK] Recherche juste avec les statuts et formations
        $enseignants = $repositoryEnseignant->findBy4($tab = array('statut' => $statut ,'formations' => $formations[0]->getNomCourt() ));
        break;
    case 3:
        // [OK] Recherche juste avec les saisies
        $enseignants = $repositoryEnseignant->findBySaisieFaite($saisieFaite);
        break;
    case 5:
        // [OK] Recherche juste avec les statuts
        $enseignants = $repositoryEnseignant->findByStatut($statut);
        break;
    case 6:
        // [OK] Recherche juste avec les formations
        $enseignants = $repositoryEnseignant->findByFormations($formations[0]->getNomCourt());
        break;
    case 7:
        // [OK] Recherche avec tout
        $enseignants = $repositoryEnseignant->findByAll();
        break;
}

$session = new Session();
$session->set('enseignants',$enseignants);
$session->set('modeleMail',$modeleMail);

        return $this->render('modele_mail/envoieMailResume.html.twig', [
            'data' => $data,
            'modeleMail' => $modeleMail,
            'enseignants' => $enseignants,
            'nomModeleMail' => $data['nom'],
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
           ->setFrom('mydispoo@gmail.com')
           ->setTo($enseignantCourant->getMail())
           ->setBody($contenu);
        $mailer->send($message);
        }
        return $this->render('modele_mail/confirmationEnvoieMail.html.twig');
    }


    /**
     * @Route("/{id}", name="modele_mail_show", methods={"GET"})
     */
    public function show(ModeleMail $modeleMail): Response
    {
        return $this->render('modele_mail/show.html.twig', [
            'modele_mail' => $modeleMail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="modele_mail_edit", methods={"GET","POST"})
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
     * @Route("/{id}", name="modele_mail_delete", methods={"DELETE"})
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
     * @Route("/supprimer/{id}", name="modele_mail_supprimer")
     */
    public function supprimer(ModeleMail $modeleMail){
        return $this->render('modele_mail/delete.html.twig', [
            'modele_mail' => $modeleMail,
        ]);
    }
}
