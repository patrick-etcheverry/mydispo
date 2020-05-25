<?php

namespace App\Controller;

use App\Entity\Remarque;
use App\Form\RemarqueType;
use App\Repository\RemarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/remarque")
 */
class RemarqueController extends AbstractController
{
    /**
     * @Route("/", name="remarque_index", methods={"GET"})
     */
    public function index(RemarqueRepository $remarqueRepository): Response
    {
        return $this->render('remarque/index.html.twig', [
            'remarques' => $remarqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="remarque_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $remarque = new Remarque();
        $form = $this->createForm(RemarqueType::class, $remarque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($remarque);
            $entityManager->flush();

            return $this->redirectToRoute('remarque_index');
        }

        return $this->render('remarque/new.html.twig', [
            'remarque' => $remarque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remarque_show", methods={"GET"})
     */
    public function show(Remarque $remarque): Response
    {
        return $this->render('remarque/show.html.twig', [
            'remarque' => $remarque,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="remarque_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Remarque $remarque): Response
    {
        $form = $this->createForm(RemarqueType::class, $remarque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('remarque_index');
        }

        return $this->render('remarque/edit.html.twig', [
            'remarque' => $remarque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remarque_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Remarque $remarque): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remarque->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($remarque);
            $entityManager->flush();
        }

        return $this->redirectToRoute('remarque_index');
    }
}
