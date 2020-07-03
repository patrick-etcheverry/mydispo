<?php

namespace App\Controller;

use App\Entity\Creneau;
use App\Form\CreneauType;
use App\Repository\CreneauRepository;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;
use \DateTimeZone;


class CreneauController extends AbstractController
{
  /**
  * @Route("/creneau/ajout", name="creneau_ajouter", methods={"POST"})
  */
  public function ajouterCreneau(EnseignantRepository $enseignantRepository ): Response
  {

    // Cette méthode doit être accessible pouur les enseignants et ne doit pas être protégée par le pare-feu


      $entityManager = $this->getDoctrine()->getManager();

      if(isset($_POST['tab'])){
    $mesCreneaux = $_POST['tab'];
    foreach ($mesCreneaux as $unCreneau) {
      $creneau = new Creneau();

      $creneau->setTitre($unCreneau['title']);
      $creneau->setDateDebut(new DateTime($unCreneau['start']));
      $creneau->getDateDebut()->setTimeZone(new DateTimeZone('Europe/Paris'));
      $creneau->setDateFin(new DateTime($unCreneau['end']));
      $creneau->getDateFin()->setTimeZone(new DateTimeZone('Europe/Paris'));
      $creneau->setType($unCreneau['type']);
      $creneau->setPrioOuPref($unCreneau['prio']);
      if($unCreneau['enseignant'] != null){
      $creneau->setEnseignant($enseignantRepository->findById($unCreneau['enseignant'])[0]);
      }
          $entityManager->persist($creneau);
    }
  }

    $entityManager->flush();

    return new Response();

  }

  /**
  * @Route("/creneau/supprimer1typedecreneau", name="suppr_creneaux" , methods={"POST"})
  */
  public function supprimerCreneauxAvantMAJ(CreneauRepository $creneauRepository)
  {

    // Cette méthode doit être accessible pouur les enseignants et ne doit pas être protégée par le pare-feu

    $entityManager = $this->getDoctrine()->getManager();

      $typeCreneau = $_POST["typeCreneau"];
      $enseignant = $_POST["idEnseignant"];

    if($enseignant != ""){
      $aSupprimer = $creneauRepository->findByTypeEtEnseignant($typeCreneau,$enseignant);
    }

    else{
      $aSupprimer = $creneauRepository->findByTypeEtEnseignant($typeCreneau,NULL);
    }


    foreach($aSupprimer as $elementASupprimer){
      $entityManager->remove($elementASupprimer);
    }
    $entityManager->flush();

    return new Response();
  }

  /**
  * @Route("/creneau/supprimer2typesdecreneaux", name="suppr_creneaux_deuxtypes" , methods={"POST"})
  */
  public function supprimerCreneaux2typesAvantMAJ(CreneauRepository $creneauRepository)
  {

    // Cette méthode doit être accessible pouur les enseignants et ne doit pas être protégée par le pare-feu

    $entityManager = $this->getDoctrine()->getManager();

      $typeCreneau1 = $_POST["typeCreneau1"];
      $typeCreneau2 = $_POST["typeCreneau2"];
      $enseignant = $_POST["idEnseignant"];

    if($enseignant != ""){
      $aSupprimer = $creneauRepository->findByTypeEtEnseignant($typeCreneau1,$enseignant);
      $aSupprimer2 =  $creneauRepository->findByTypeEtEnseignant($typeCreneau2,$enseignant);
    }

    else{
      $aSupprimer = $creneauRepository->findByTypeEtEnseignant($typeCreneau1,NULL);
      $aSupprimer2 = $creneauRepository->findByTypeEtEnseignant($typeCreneau2,NULL);
    }

    foreach ($aSupprimer2 as $creneauxCourant) {
     array_push($aSupprimer,$creneauxCourant);
    }

    foreach($aSupprimer as $elementASupprimer){
      $entityManager->remove($elementASupprimer);
    }
    $entityManager->flush();

    return new Response();
  }



  /**
  * @Route("/admin/creneau/", name="creneau_index", methods={"GET"})
  */
  public function index(CreneauRepository $creneauRepository): Response
  {

    // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

    return $this->render('creneau/index.html.twig', [
      'creneaus' => $creneauRepository->findAll(),
    ]);
  }

  /**
  * @Route("/admin/creneau/new", name="creneau_new", methods={"GET","POST"})
  */
  public function new(Request $request): Response
  {

    // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu


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
  * @Route("/admin/creneau/{id}", name="creneau_show", methods={"GET"})
  */
  public function show(Creneau $creneau): Response
  {

    // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

    return $this->render('creneau/show.html.twig', [
      'creneau' => $creneau,
    ]);
  }

  /**
  * @Route("/admin/creneau/edit/{id}", name="creneau_edit", methods={"GET","POST"})
  */
  public function edit(Request $request, Creneau $creneau): Response
  {

    // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

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
  * @Route("/admin/creneau/delete/{id}", name="creneau_delete", methods={"DELETE"})
  */
  public function delete(Request $request, Creneau $creneau): Response
  {

    // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

    if ($this->isCsrfTokenValid('delete'.$creneau->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($creneau);
      $entityManager->flush();
    }

    return $this->redirectToRoute('creneau_index');
  }






}
