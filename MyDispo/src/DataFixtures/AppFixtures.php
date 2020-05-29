<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Remarque;
use App\Entity\Enseignant;
use App\Entity\Formation;
use App\Entity\ModeleMail;
use \Datetime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // Création d'un générateur de données Faker
        $faker = \Faker\Factory::create('fr_FR');


        //Création d'enseignants
        $enseignant1 = new Enseignant();
        $enseignant1->setNom('Marquesuzaà');
        $enseignant1->setPrenom('Christophe');
        $enseignant1->setMail('thombouchet@aol.com');
        $enseignant1->setStatut('Titulaire');
        $enseignant1->setEnSommeil(false);
        $enseignant1->setToken('https://iutbayonne/formulaire/gtauzfeduig.fr');
        $enseignant1->setSaisieFaite(true);
        $enseignant1->setDateSaisie(new DateTime('12/07/2020'));
        $enseignant1->setDateDerniereModif(null);
        $enseignant1->setMailRelanceRecu(true);
        $enseignant1->setPremierMailRecu(true);
        $enseignant1->setDatePremierMail(new DateTime('05/06/2020'));
        $enseignant1->setDateDerniereRelance(new DateTime('12/07/2020'));
        $enseignant1->setNbRelance(1);

        $manager->persist($enseignant1);

        $enseignant2 = new Enseignant();
        $enseignant2->setNom('Roose');
        $enseignant2->setPrenom('Philippe');
        $enseignant2->setMail('thombouchet@aol.com');
        $enseignant2->setStatut('Titulaire');
        $enseignant2->setEnSommeil(true);
        $enseignant2->setToken('https://iutbayonne/formulaire/qiuOfeduig.fr');
        $enseignant2->setSaisieFaite(false);
        $enseignant2->setDateSaisie(null);
        $enseignant2->setDateDerniereModif(null);
        $enseignant2->setMailRelanceRecu(false);
        $enseignant2->setPremierMailRecu(true);
        $enseignant2->setDatePremierMail(new DateTime('05/06/2020'));
        $enseignant2->setDateDerniereRelance(null);
        $enseignant2->setNbRelance(0);

        $manager->persist($enseignant2);

        $enseignant3 = new Enseignant();
        $enseignant3->setNom('Corbineau');
        $enseignant3->setPrenom('Sandrine');
        $enseignant3->setMail('thombouchet@aol.com');
        $enseignant3->setStatut('Vacataire');
        $enseignant3->setEnSommeil(false);
        $enseignant3->setToken('https://iutbayonne/formulaire/lqIZSURHduig.fr');
        $enseignant3->setSaisieFaite(true);
        $enseignant3->setDateSaisie(new DateTime('08/06/2020'));
        $enseignant3->setDateDerniereModif(new DateTime('05/06/2020'));
        $enseignant3->setMailRelanceRecu(true);
        $enseignant3->setPremierMailRecu(true);
        $enseignant3->setDatePremierMail(new DateTime('05/06/2020'));
        $enseignant3->setDateDerniereRelance(new DateTime('09/06/2020'));
        $enseignant3->setNbRelance(2);


        $manager->persist($enseignant3);

        $enseignant4 = new Enseignant();
        $enseignant4->setNom('Alami');
        $enseignant4->setPrenom('Chakib');
        $enseignant4->setMail('thombouchet@aol.com');
        $enseignant4->setStatut('Titulaire');
        $enseignant4->setEnSommeil(false);
        $enseignant4->setToken('https://iutbayonne/formulaire/gQLOIUSHYeduig.fr');
        $enseignant4->setSaisieFaite(false);
        $enseignant4->setDateSaisie(null);
        $enseignant4->setDateDerniereModif(null);
        $enseignant4->setMailRelanceRecu(false);
        $enseignant4->setPremierMailRecu(false);
        $enseignant4->setDatePremierMail(null);
        $enseignant4->setDateDerniereRelance(null);
        $enseignant4->setNbRelance(0);


        $manager->persist($enseignant4);



        //Création des formations
        $formationDUT = new Formation();
        $formationDUT->setNomCourt('DUT Info');
        $formationDUT->setNomLong('Diplôme Universitaire de technologie Informatique');
        $formationDUT->addEnseignant($enseignant1);
        $formationDUT->addEnseignant($enseignant2);

        $manager->persist($formationDUT);

        $formationLP = new Formation();
        $formationLP->setNomCourt('LP Info');
        $formationLP->setNomLong('License Professionnelle Informatique');
        $formationLP->addEnseignant($enseignant3);
        $formationLP->addEnseignant($enseignant4);
        $formationLP->addEnseignant($enseignant2);


        $manager->persist($formationLP);

/*
                //Création de remarques
                $Nbremarques = 4;
                new $listeEnseignants = array('1' => $enseignant1,'2' => $enseignant2,'3' => $enseignant3,'4' => $enseignant4);
                for($i = 1; $i <= $Nbremarques ; $i++){
                  $remarque =  new Remarque ();
                  $remarque->setContenu($faker->text($maxNbChars = 200));
                  $remarque->setType($faker->randomElement($array = array ('Ponctuelle','Hebdomadaire')));
                  $remarque->setEnseignant($listeEnseignants[$i]);

                  $manager->persist($remarque);
                }
*/

        // Création des modèles de mails
        $modeleMailPremier = new ModeleMail();
        $modeleMailPremier->setNom('Mail premier contact');
        $modeleMailPremier->setSujet('Saisie de vos contraintes et disponibilités IUT Anglet');
        $modeleMailPremier->setContenu('Bonjour, ....');

        $manager->persist($modeleMailPremier);

        $modeleMailRelance = new ModeleMail();
        $modeleMailRelance->setNom('Mail de relance');
        $modeleMailRelance->setSujet('Relance pour la saisie de vos contraintes et disponibilités IUT Anglet');
        $modeleMailRelance->setContenu('Bonjour, nous vous relancons....');

        $manager->persist($modeleMailRelance);

        $modeleMailOubli = new ModeleMail();
        $modeleMailOubli->setNom('Mail oubli');
        $modeleMailOubli->setSujet('Lien oublié saisie de vos contraintes IUT Anglet');
        $modeleMailOubli->setContenu('Bonjour, vous trouverez ci-dessous ...');

        $manager->persist($modeleMailOubli);

        $formulaireTitulaire= new FormulaireTitulaire();
        $formulaireTitulaire->setEchelleCalendrier(60);
        $formulaireTitulaire->setTexteHebdomadaire("");
        $formulaireTitulaire->setTextePonctuel("");
        $formulaireTitulaire->setRemarquesHebdoActives(true);
        $formulaireTitulaire->setRemarquesPonctuelActives(true);
        $formulaireTitulaire->setEstOuvert(true);

        $formulaireTitulaire->setQuantiteProForte(1);
        $formulaireTitulaire->setDureeProForte(60);
        $formulaireTitulaire->setQuantiteProMoy(1);
        $formulaireTitulaire->setDureeProMoy(60);
        $formulaireTitulaire->setQuantiteProFaible(1);
        $formulaireTitulaire->setDureeProFaible(60);

        $formulaireTitulaire->setQuantitePersForte(1);
        $formulaireTitulaire->setDureePersForte(60);
        $formulaireTitulaire->setQuantitePersMoy(1);
        $formulaireTitulaire->setDureePersMoy(60);
        $formulaireTitulaire->setQuantitePersFaible(1);
        $formulaireTitulaire->setDureePersFaible(60);


        $manager->persist($formulaireTitulaire);


        // Envoyer les données en BD
        $manager->flush();

    }
}
