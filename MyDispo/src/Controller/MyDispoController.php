<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use StdClass;
use App\Repository\CreneauRepository;
use App\Repository\FormulaireTitulaireRepository;
use App\Repository\FormulaireVacataireRepository;

class MyDispoController extends AbstractController
{
  /**
  * @Route("/parametres/generaux", name="parametres_gen")
  */
  public function index(CreneauRepository $creneauRepository,FormulaireTitulaireRepository $formTitulaireRepository, FormulaireVacataireRepository $formVacataireRepository)
  {
    $myarray = array();
    $events = $creneauRepository->selectStartEndTitleByType("zoneGrisee");
    foreach ($events as $event){
      $object = new StdClass;
      $object->title=$event["title"];
      $object->daysOfWeek=date('w',$event["start"]->getTimestamp());
      $object->startTime=$event["start"]->format("H:i:s");
      $object->endTime=$event["end"]->format("H:i:s");
      $myarray[] = $object;
    }
    $result=json_encode($myarray);

    $echelle1=$formTitulaireRepository->selectEchelleCalendrier()[0]["echelleCalendrier"];
    $echelle2=$formVacataireRepository->selectEchelleCalendrier()[0]["echelleCalendrier"];
    if($echelle1>$echelle2){$echelle=$echelle2;}
    else{$echelle=$echelle1;}

    return $this->render('my_dispo/index.html.twig', [
      'events' => $result,
      'echelle' => $echelle,
    ]);
  }
}
