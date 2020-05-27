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
    }
    return $this->render('modele_mail/envoieMail.html.twig', [
        'form' => $form->createView(),
    ]);
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
