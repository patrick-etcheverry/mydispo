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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use \Swift_Mailer;
use \Swift_SmtpTransport;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Persistence\ObjectManager;
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
                'label' => 'Type de mail :',
                'multiple' => false,
                'expanded' => false,
                'required' => true
            ))
            ->add('nomCourt', EntityType::class, array(
                    'class' => Formation::class,
                    'choice_label' => 'nomCourt',
                    'label' => 'Enseignant intervenant en :',
                    'multiple' => true,
                    'expanded' => true,
                    'required' => true
                ))
                ->add('statut', ChoiceType::class, array(
                        'choices' => [
                          'Titulaire' => 'Titulaire',
                          'Vacataire' => 'Vacataire',
                          ],
                        'label' => 'Enseignant ayant le statut de :',
                        'multiple' => true,
                        'expanded' => true,
                        'required' => true
                    ))
                ->add('saisieFaite', ChoiceType::class, array(
                        'choices' => [
                          'Une saisie effectuée' => true,
                          'Une saisie non effectuée' => false,
                          ],
                        'label' => 'Enseignant avec : ',
                        'multiple' => true,
                        'expanded' => true,
                        'required' => true
                    ))
                    ->add('mailRelanceRecu', ChoiceType::class, array(
                            'choices' => [
                              'Ayant reçu un mail de relance' => true,
                              'N\'ayant pas reçu de mail de relance' => false,
                              ],
                            'label' => 'Enseignant : ',
                            'multiple' => true,
                            'expanded' => true,
                            'required' => true
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
      $mailRelanceRecu = $form["mailRelanceRecu"]->getData();
      $modeleMail = $repositoryModeleMail->findOneByNomModeleMail($nom);

      $enseignants = $repositoryEnseignant->findByGeneral($tab = array('saisieFaite' => $saisieFaite ,'statut' => $statut, 'formations' => $formations, 'mailRelanceRecu' => $mailRelanceRecu ));


$session = new Session();
$session->set('enseignants',$enseignants);
$session->set('modeleMail',$modeleMail);


        return $this->render('modele_mail/envoieMailResume.html.twig', [
            'data' => $data,
            'modeleMail' => $modeleMail,
            'enseignants' => $enseignants,
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
           ->setFrom(['mydispoo@gmail.com' => 'Patrick Etcheverry'])
           ->setTo($enseignantCourant->getMail())
           ->setBody($contenu);
        $mailer->send($message);
        }
        return $this->render('modele_mail/confirmationEnvoieMail.html.twig');
    }

    /**
     * @Route("/premierMail/{id}/{compteur}", name="notifierUnEnseignant_PremierMail", methods={"GET"})
     */
    public function notifierUnEnseignantPremierMail(Enseignant $enseignant , int $compteur)
    {
      new \DateTimeZone('Europe/Paris');
      switch ($compteur)
      {
        case 1 :
            $nom = 'Mail premier contact';
            $enseignant->setPremierMailRecu(true);
            $date1 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $date1->setTimezone(new \DateTimeZone('UTC'));
            $enseignant->setDatePremierMail($date1);
            break;
        case 2 :
            $nom = 'Mail de relance';
            $enseignant->setMailRelanceRecu(true);
            $date2 = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $date2->setTimezone(new \DateTimeZone('UTC'));
            $enseignant->setDateDerniereRelance($date2);
            $enseignant->setNbRelance($enseignant->getNbRelance() +1);

            break;
        case 3 :
            $nom = 'Mail oubli';
            break;
      }
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($enseignant);
      $entityManager->flush();


            $repositoryModeleMail = $this->getDoctrine()->getRepository(ModeleMail::class);
            $modeleMail = $repositoryModeleMail->findOneByNomModeleMail($nom);

            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465))
              ->setHost('smtp.gmail.com')
              ->setPort('465')
              ->setEncryption('ssl')
              ->setAuthMode('login')
              ->setUsername($_ENV['MAILER_USER'])
              ->setPassword($_ENV['MAILER_PASSWORD']);
          $mailer = new \Swift_Mailer($transport);
          $message = (new \Swift_Message($modeleMail->getSujet()))
             ->setFrom($_ENV['MAILER_USER'])
             ->setTo($enseignant->getMail())
             ->setBody($modeleMail->getContenu());
          $mailer->send($message);

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
