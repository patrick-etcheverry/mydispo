<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Remarque;
use App\Entity\Enseignant;
use App\Entity\Formation;
use App\Entity\ModeleMail;
use App\Entity\FormulaireTitulaire;
use App\Entity\FormulaireVacataire;
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

        $enseignant5 = new Enseignant();
        $enseignant5->setNom('Dalmau');
        $enseignant5->setPrenom('Marc');
        $enseignant5->setMail('thombouchet@aol.com');
        $enseignant5->setStatut('Titulaire');
        $enseignant5->setEnSommeil(false);
        $enseignant5->setToken('https://iutbayonne/formulaire/gtauzfeduig.fr');
        $enseignant5->setSaisieFaite(true);
        $enseignant5->setDateSaisie(new DateTime('12/07/2020'));
        $enseignant5->setDateDerniereModif(null);
        $enseignant5->setMailRelanceRecu(false);
        $enseignant5->setPremierMailRecu(true);
        $enseignant5->setDatePremierMail(new DateTime('05/06/2020'));
        $enseignant5->setDateDerniereRelance(null);
        $enseignant5->setNbRelance(1);

        $manager->persist($enseignant5);

        $enseignant6 = new Enseignant();
        $enseignant6->setNom('Urruty');
        $enseignant6->setPrenom('Damien');
        $enseignant6->setMail('thombouchet@aol.com');
        $enseignant6->setStatut('Vacataire');
        $enseignant6->setEnSommeil(true);
        $enseignant6->setToken('https://iutbayonne/formulaire/qiuOfeduig.fr');
        $enseignant6->setSaisieFaite(false);
        $enseignant6->setDateSaisie(null);
        $enseignant6->setDateDerniereModif(null);
        $enseignant6->setMailRelanceRecu(false);
        $enseignant6->setPremierMailRecu(true);
        $enseignant6->setDatePremierMail(new DateTime('05/06/2020'));
        $enseignant6->setDateDerniereRelance(null);
        $enseignant6->setNbRelance(0);

        $manager->persist($enseignant6);

        $enseignant7 = new Enseignant();
        $enseignant7->setNom('Lopistheguy');
        $enseignant7->setPrenom('Philippe');
        $enseignant7->setMail('thombouchet@aol.com');
        $enseignant7->setStatut('Titulaire');
        $enseignant7->setEnSommeil(false);
        $enseignant7->setToken('https://iutbayonne/formulaire/lqIZSURHduig.fr');
        $enseignant7->setSaisieFaite(false);
        $enseignant7->setDateSaisie(null);
        $enseignant7->setDateDerniereModif(null);
        $enseignant7->setMailRelanceRecu(false);
        $enseignant7->setPremierMailRecu(false);
        $enseignant7->setDatePremierMail(null);
        $enseignant7->setDateDerniereRelance(null);
        $enseignant7->setNbRelance(2);


        $manager->persist($enseignant7);

        $enseignant8 = new Enseignant();
        $enseignant8->setNom('Laplace');
        $enseignant8->setPrenom('Sophie');
        $enseignant8->setMail('thombouchet@aol.com');
        $enseignant8->setStatut('Titulaire');
        $enseignant8->setEnSommeil(false);
        $enseignant8->setToken('https://iutbayonne/formulaire/gQLOIUSHYeduig.fr');
        $enseignant8->setSaisieFaite(true);
        $enseignant8->setDateSaisie(new DateTime('06/06/2020'));
        $enseignant8->setDateDerniereModif(new DateTime('05/06/2020'));
        $enseignant8->setMailRelanceRecu(false);
        $enseignant8->setPremierMailRecu(true);
        $enseignant8->setDatePremierMail(null);
        $enseignant8->setDateDerniereRelance(null);
        $enseignant8->setNbRelance(0);


        $manager->persist($enseignant8);

        //Création des formations
        $formationDUT = new Formation();
        $formationDUT->setNomCourt('DUT Info');
        $formationDUT->setNomLong('Diplôme Universitaire de technologie Informatique');
        $formationDUT->addEnseignant($enseignant1);
        $formationDUT->addEnseignant($enseignant2);
        $formationDUT->addEnseignant($enseignant5);
        $formationDUT->addEnseignant($enseignant6);
        $formationDUT->addEnseignant($enseignant7);

        $manager->persist($formationDUT);

        $formationLP = new Formation();
        $formationLP->setNomCourt('LP Info');
        $formationLP->setNomLong('License Professionnelle Informatique');
        $formationLP->addEnseignant($enseignant3);
        $formationLP->addEnseignant($enseignant4);
        $formationLP->addEnseignant($enseignant2);
        $formationLP->addEnseignant($enseignant6);
        $formationLP->addEnseignant($enseignant8);


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
        $modeleMailPremier->setContenu(
          'Bonjour,

           L\'année universitaire 2020-2021 approche !
           Je vous contact aujourd\'hui dans le but de saisir vos disponibilités et contraintes pour l\'année 2020-2021.
           Pour trouverez en suivant un lien vous permettant de saisir vos contraintes et disponibilités sur notre nouveau site web.

           Lien :

           Si vous avez des questions ou des remarques contactez-moi.

           Cordialement,

           Patrick Etcheverry

          '
        );

        $manager->persist($modeleMailPremier);

        $modeleMailRelance = new ModeleMail();
        $modeleMailRelance->setNom('Mail de relance');
        $modeleMailRelance->setSujet('Relance pour la saisie de vos contraintes et disponibilités IUT Anglet');
        $modeleMailRelance->setContenu(
          'Bonjour,

           L\'année universitaire 2020-2021 approche !
           Je vous relance suite à la saisie de vos disponibilités et contraintes pour l\'universitaire 2020-2021.
           Veuillez-vous rendre sur le lien ci-joint pour effectuer vos saisies.
           Lien :

           Si vous avez des questions ou des remarques contactez-moi.

           Cordialement,

           Patrick Etcheverry

          '
            );
        $manager->persist($modeleMailRelance);

        $modeleMailOubli = new ModeleMail();
        $modeleMailOubli->setNom('Mail oubli');
        $modeleMailOubli->setSujet('Lien oublié saisie de vos contraintes IUT Anglet');
        $modeleMailOubli->setContenu(

            'Bonjour,

             Vous avez oublié votre lien personnalisé pour la saisie de vos contraintes et disponibilités pour l\'année 2020-2021 ?
             Voici votre lien :

             Si vous avez des questions ou des remarques contactez-moi.

             Cordialement,

             Patrick Etcheverry

            '
              );

        $manager->persist($modeleMailOubli);

        $formulaireTitulaire= new FormulaireTitulaire();
        $formulaireTitulaire->setEchelleCalendrier(60);
        $formulaireTitulaire->setTexteHebdomadaire("Saisir texte ici");
        $formulaireTitulaire->setTextePonctuel("Saisir texte ici");
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
        $formulaireTitulaire->setHeureDebutCalendrier(new DateTime('08:00:00'));
        $formulaireTitulaire->setHeureFinCalendrier(new DateTime('18:00:00'));


        $manager->persist($formulaireTitulaire);

        $formulaireVacataire= new FormulaireVacataire();
        $formulaireVacataire->setEchelleCalendrier(60);
        $formulaireVacataire->setTexteHebdomadaire("Saisir texte ici");
        $formulaireVacataire->setTextePonctuel("Saisir texte ici");
        $formulaireVacataire->setRemarquesHebdoActives(true);
        $formulaireVacataire->setRemarquesPonctuelActives(true);
        $formulaireVacataire->setEstOuvert(true);
        $formulaireVacataire->setHeureDebutCalendrier(new DateTime('08:00:00'));
        $formulaireVacataire->setHeureFinCalendrier(new DateTime('18:00:00'));



        $manager->persist($formulaireVacataire);


        // Envoyer les données en BD
        $manager->flush();

    }
}
