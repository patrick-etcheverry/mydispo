<?php

namespace App\Controller;

use App\Entity\ModeleMail;
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
}
