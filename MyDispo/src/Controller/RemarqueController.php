<?php

namespace App\Controller;

use App\Entity\Remarque;
use App\Form\RemarqueType;
use App\Repository\RemarqueRepository;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/remarque")
 */
class RemarqueController extends AbstractController
{
  /**
  * @Route("/remarque/ajout", name="remarque_ajouter", methods={"POST"})
  */
  public function ajouterRemarque(EnseignantRepository $enseignantRepository ): Response
  {

    $remarque1 = new Remarque();
    $remarque2 = new Remarque();

    $contenu1 = $_POST["contenu1"];
    $type1 = $_POST["type1"];
    $contenu2 = $_POST["contenu2"];
    $type2 = $_POST["type2"];
    $idEnseignant = $_POST["idEnseignant"];

    $remarque1->setContenu($contenu1);
    $remarque1->setType($type1);

    $remarque2->setContenu($contenu2);
    $remarque2->setType($type2);

    if($idEnseignant != ""){
    $remarque1->setEnseignant($enseignantRepository->findById($idEnseignant)[0]);
    $remarque2->setEnseignant($enseignantRepository->findById($idEnseignant)[0]);
    }

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($remarque1);
    $entityManager->persist($remarque2);
    $entityManager->flush();

    return new Response();

  }

  /**
  * @Route("/supprimer/remarques", name="suppr_remarque" , methods={"POST"})
  */
  public function supprimerRemarquesAvantMAJ(RemarqueRepository $remarqueRepository)
  {
    $entityManager = $this->getDoctrine()->getManager();

    $enseignant = $_POST["idEnseignant"];
    $aSupprimer = $remarqueRepository->findByEnseignant($enseignant);

    foreach($aSupprimer as $elementASupprimer){
      $entityManager->remove($elementASupprimer);
    }
    $entityManager->flush();

    return new Response();
  }




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
