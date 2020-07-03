<?php

namespace App\Controller;

use App\Entity\LogEnseignant;
use App\Form\LogEnseignantType;
use App\Repository\LogEnseignantRepository;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class LogEnseignantController extends AbstractController
{
    /**
     * @Route("/admin/log/enseignant/", name="log_enseignant_index", methods={"GET"})
     */
    public function index(LogEnseignantRepository $logEnseignantRepository): Response
    {
      // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

        return $this->render('log_enseignant/index.html.twig', [
            'log_enseignants' => $logEnseignantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/log/enseignant/new", name="log_enseignant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
      // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

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
     * @Route("/admin/log/enseignant/{idEnseignant}/{idLog}", name="log_enseignant_show", methods={"GET"})
     */
    public function show($idEnseignant, $idLog,LogEnseignantRepository $logEnseignantRepository, enseignantRepository $enseignantRepository): Response
    {
      // Cette méthode du CRUD est utilisée par l'admin, on la protège derrière le pare-feu


      // Permet de retrouver les logs de l'enseignant
      $enseignantCible = $enseignantRepository->findOneById($idEnseignant);
      $logs = $logEnseignantRepository->findByEnseignantId($enseignantCible);

      // Trouver la date du log
      $log = $logEnseignantRepository->findOneById($idLog);

      $logsFinal = [];
      // Recherche les logs de l'enseignant avec la même dateLog
      foreach ($logs as $logCourant) {
        if($log->getDateLog()->format('Y-m-d') == $logCourant->getDateLog()->format('Y-m-d')  ){
          array_push($logsFinal,$logCourant);
        }
      }


        return $this->render('log_enseignant/show.html.twig', [
            'logs_enseignant' => $logsFinal,
        ]);
    }

    /**
     * @Route("/admin/log/enseignant/edit/{id}", name="log_enseignant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LogEnseignant $logEnseignant): Response
    {

      // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

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
     * @Route("/admin/log/enseignant/delete/{id}", name="log_enseignant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LogEnseignant $logEnseignant): Response
    {

      // Cette méthode du CRUD n'est pas utilisée, on la protège derrière le pare-feu

        if ($this->isCsrfTokenValid('delete'.$logEnseignant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($logEnseignant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('log_enseignant_index');
    }


    /**
     * @Route("/log/enseignant/setLogHebdo", name="set_log_enseignant_hebdo", methods={"POST"})
     */
    public function setNewLogHebdo(EnseignantRepository $enseignantRepository)
    {

      // Cette méthode doit être accessible pouur les enseignants et ne doit pas être protégée par le pare-feu

      //Récupération des données
        $deltaRemarqueHebdo = $_POST["tabDeltaRemarqueHebdo"];
        $deltaCreneauxHebdo = $_POST["tabDeltaCreneauxHebdo"];
        $idEnseignant = $_POST["idEnseignant"];

        $description = "";

        $logEnseignant = new LogEnseignant();
        $logEnseignant->setDateLog(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

        if($deltaRemarqueHebdo == "Aucune modif remarque" && $deltaCreneauxHebdo == "Aucune modif créneau"){
          $logEnseignant->setTypeAction("Consultation");
          $logEnseignant->setZone("Aucune zone");
        }
        else{
          $logEnseignant->setTypeAction("Modification");
          $logEnseignant->setZone("Zone saisie hebdomadaire");
        }

        if($deltaRemarqueHebdo != "Aucune modif remarque"){
        foreach ($deltaRemarqueHebdo as $deltaRemarqueHebdoCourant) {
          $description .= $deltaRemarqueHebdoCourant."  ";
        }
      }
      if($deltaCreneauxHebdo != "Aucune modif créneau"){
        foreach ($deltaCreneauxHebdo as $deltaCreneauxHebdoCourant) {
          $description .= $deltaCreneauxHebdoCourant."  ";
        }
      }
        $logEnseignant->setDescription($description);
        $logEnseignant->setEnseignant($enseignantRepository->findOneById($idEnseignant));


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($logEnseignant);
        $entityManager->flush();



        return new Response();
    }


    /**
     * @Route("/log/enseignant/setLogPonctu", name="set_log_enseignant_ponctu", methods={"POST"})
     */
    public function setNewLogPonctu(EnseignantRepository $enseignantRepository)
    {

      // Cette méthode doit être accessible pouur les enseignants et ne doit pas être protégée par le pare-feu

      //Récupération des données
        $deltaRemarquePonctu = $_POST["tabDeltaRemarquePonctu"];
        $deltaCreneauxPonctu = $_POST["tabDeltaCreneauxPonctu"];
        $idEnseignant = $_POST["idEnseignant"];

        $description = "";

        $logEnseignant = new LogEnseignant();
        $logEnseignant->setDateLog(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

        if($deltaRemarquePonctu == "Aucune modif remarque" && $deltaCreneauxPonctu == "Aucune modif créneau"){
          $logEnseignant->setTypeAction("Consultation");
          $logEnseignant->setZone("Aucune zone");
        }
        else{
          $logEnseignant->setTypeAction("Modification");
          $logEnseignant->setZone("Zone saisie ponctuelle");
        }

        if($deltaRemarquePonctu != "Aucune modif remarque"){
        foreach ($deltaRemarquePonctu as $deltaRemarquePonctuCourant) {
          $description .= $deltaRemarquePonctuCourant."  ";
        }
      }
      if($deltaCreneauxPonctu != "Aucune modif créneau"){
        foreach ($deltaCreneauxPonctu as $deltaCreneauxPonctuCourant) {
          $description .= $deltaCreneauxPonctuCourant."  ";
        }
      }
        $logEnseignant->setDescription($description);
        $logEnseignant->setEnseignant($enseignantRepository->findOneById($idEnseignant));


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($logEnseignant);
        $entityManager->flush();



        return new Response();
    }
}
