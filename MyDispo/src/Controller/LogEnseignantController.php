<?php

namespace App\Controller;

use App\Entity\LogEnseignant;
use App\Form\LogEnseignantType;
use App\Repository\LogEnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/log/enseignant")
 */
class LogEnseignantController extends AbstractController
{
    /**
     * @Route("/", name="log_enseignant_index", methods={"GET"})
     */
    public function index(LogEnseignantRepository $logEnseignantRepository): Response
    {
        return $this->render('log_enseignant/index.html.twig', [
            'log_enseignants' => $logEnseignantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="log_enseignant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $logEnseignant = new LogEnseignant();
        $form = $this->createForm(LogEnseignantType::class, $logEnseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($logEnseignant);
            $entityManager->flush();

            return $this->redirectToRoute('log_enseignant_index');
        }

        return $this->render('log_enseignant/new.html.twig', [
            'log_enseignant' => $logEnseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="log_enseignant_show", methods={"GET"})
     */
    public function show(LogEnseignant $logEnseignant): Response
    {
        return $this->render('log_enseignant/show.html.twig', [
            'log_enseignant' => $logEnseignant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="log_enseignant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LogEnseignant $logEnseignant): Response
    {
        $form = $this->createForm(LogEnseignantType::class, $logEnseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('log_enseignant_index');
        }

        return $this->render('log_enseignant/edit.html.twig', [
            'log_enseignant' => $logEnseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="log_enseignant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LogEnseignant $logEnseignant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$logEnseignant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($logEnseignant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('log_enseignant_index');
    }
}
