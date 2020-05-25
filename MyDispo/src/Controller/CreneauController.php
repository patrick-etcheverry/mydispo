<?php

namespace App\Controller;

use App\Entity\Creneau;
use App\Form\CreneauType;
use App\Repository\CreneauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/creneau")
 */
class CreneauController extends AbstractController
{
    /**
     * @Route("/", name="creneau_index", methods={"GET"})
     */
    public function index(CreneauRepository $creneauRepository): Response
    {
        return $this->render('creneau/index.html.twig', [
            'creneaus' => $creneauRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="creneau_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $creneau = new Creneau();
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($creneau);
            $entityManager->flush();

            return $this->redirectToRoute('creneau_index');
        }

        return $this->render('creneau/new.html.twig', [
            'creneau' => $creneau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="creneau_show", methods={"GET"})
     */
    public function show(Creneau $creneau): Response
    {
        return $this->render('creneau/show.html.twig', [
            'creneau' => $creneau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="creneau_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Creneau $creneau): Response
    {
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('creneau_index');
        }

        return $this->render('creneau/edit.html.twig', [
            'creneau' => $creneau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="creneau_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Creneau $creneau): Response
    {
        if ($this->isCsrfTokenValid('delete'.$creneau->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($creneau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('creneau_index');
    }
}
