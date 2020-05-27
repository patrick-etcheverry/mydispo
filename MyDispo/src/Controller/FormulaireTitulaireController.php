<?php

namespace App\Controller;

use App\Entity\FormulaireTitulaire;
use App\Form\FormulaireTitulaireType;
use App\Repository\FormulaireTitulaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formulaire/titulaire")
 */
class FormulaireTitulaireController extends AbstractController
{
    /**
     * @Route("/", name="formulaire_titulaire_index", methods={"GET"})
     */
    public function index(FormulaireTitulaireRepository $formulaireTitulaireRepository): Response
    {
        return $this->render('formulaire_titulaire/index.html.twig', [
            'formulaire_titulaires' => $formulaireTitulaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="formulaire_titulaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formulaireTitulaire = new FormulaireTitulaire();
        $form = $this->createForm(FormulaireTitulaireType::class, $formulaireTitulaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formulaireTitulaire);
            $entityManager->flush();

            return $this->redirectToRoute('formulaire_titulaire_index');
        }

        return $this->render('formulaire_titulaire/new.html.twig', [
            'formulaire_titulaire' => $formulaireTitulaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formulaire_titulaire_show", methods={"GET"})
     */
    public function show(FormulaireTitulaire $formulaireTitulaire): Response
    {
        return $this->render('formulaire_titulaire/show.html.twig', [
            'formulaire_titulaire' => $formulaireTitulaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formulaire_titulaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FormulaireTitulaire $formulaireTitulaire): Response
    {
        $form = $this->createForm(FormulaireTitulaireType::class, $formulaireTitulaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formulaire_titulaire_index');
        }

        return $this->render('formulaire_titulaire/edit.html.twig', [
            'formulaire_titulaire' => $formulaireTitulaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formulaire_titulaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FormulaireTitulaire $formulaireTitulaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formulaireTitulaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formulaireTitulaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formulaire_titulaire_index');
    }
}
