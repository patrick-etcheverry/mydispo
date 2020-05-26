<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Remarque;
use App\Entity\Enseignant;
use App\Entity\Formation;


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
        $enseignant1->setMail('marqua@gmail.com');
        $enseignant1->setStatut('Titulaire');
        $enseignant1->setEnSommeil(false);
        $enseignant1->setToken('https://iutbayonne/formulaire/gtauzfeduig.fr');
        $enseignant1->setSaisieFaite(true);

        $manager->persist($enseignant1);

        $enseignant2 = new Enseignant();
        $enseignant2->setNom('Roose');
        $enseignant2->setPrenom('Philippe');
        $enseignant2->setMail('roose@gmail.com');
        $enseignant2->setStatut('Titulaire');
        $enseignant2->setEnSommeil(false);
        $enseignant2->setToken('https://iutbayonne/formulaire/qiuOfeduig.fr');
        $enseignant2->setSaisieFaite(false);

        $manager->persist($enseignant2);

        $enseignant3 = new Enseignant();
        $enseignant3->setNom('Corbineau');
        $enseignant3->setPrenom('Sandrine');
        $enseignant3->setMail('corbi@gmail.com');
        $enseignant3->setStatut('Vacataire');
        $enseignant3->setEnSommeil(false);
        $enseignant3->setToken('https://iutbayonne/formulaire/lqIZSURHduig.fr');
        $enseignant3->setSaisieFaite(false);

        $manager->persist($enseignant3);

        $enseignant4 = new Enseignant();
        $enseignant4->setNom('Alami');
        $enseignant4->setPrenom('Chakib');
        $enseignant4->setMail('alami@gmail.com');
        $enseignant4->setStatut('Titulaire');
        $enseignant4->setEnSommeil(false);
        $enseignant4->setToken('https://iutbayonne/formulaire/gQLOIUSHYeduig.fr');
        $enseignant4->setSaisieFaite(true);

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
                $Nbremarques = 5;

                for($i = 0; $i < $Nbremarques ; $i++){
                  $remarque =  new Remarque ();
                  $remarque->setContenu($faker->text($maxNbChars = 200));
                  $remarque->setType($faker->randomElement($array = array ('Ponctuelle','Hebdomadaire')));
                  $remarque->setEnseignant($enseignant1);

                  $manager->persist($remarque);
                }
        */


        // Envoyer les données en BD
        $manager->flush();

    }
}
