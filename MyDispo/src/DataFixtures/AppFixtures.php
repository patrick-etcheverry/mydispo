<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Remarque;
use App\Entity\LogEnseignant;
use App\Entity\Creneau;
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


        //Création de remarques

        $remarque1 =  new Remarque ();
        $remarque1->setContenu($faker->text($maxNbChars = 200));
        $remarque1->setType($faker->randomElement($array = array ('Ponctuelle','Hebdomadaire')));
        $remarque1->setEnseignant($enseignant1);

        $manager->persist($remarque1);

        $remarque2 =  new Remarque ();
        $remarque2->setContenu($faker->text($maxNbChars = 200));
        $remarque2->setType($faker->randomElement($array = array ('Ponctuelle','Hebdomadaire')));
        $remarque2->setEnseignant($enseignant2);

        $manager->persist($remarque2);

        $remarque3 =  new Remarque ();
        $remarque3->setContenu($faker->text($maxNbChars = 200));
        $remarque3->setType($faker->randomElement($array = array ('Ponctuelle','Hebdomadaire')));
        $remarque3->setEnseignant($enseignant3);

        $manager->persist($remarque3);

        $remarque4 =  new Remarque ();
        $remarque4->setContenu($faker->text($maxNbChars = 200));
        $remarque4->setType($faker->randomElement($array = array ('Ponctuelle','Hebdomadaire')));
        $remarque4->setEnseignant($enseignant3);

        $manager->persist($remarque4);

        //Création des créneaux

        $creneau1 = new Creneau();
        $creneau1->setEnseignant($enseignant1);
        $creneau1->setTitre($faker->text($maxNbChars = 20));
        $creneau1->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau1->setDateDebut(new DateTime('06/06/2020'));
        $creneau1->setDateFin(new DateTime('06/07/2020'));
        $creneau1->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro','ZoneGrisee','Evenement','Disponibilite')));

        $manager->persist($creneau1);

        $creneau2 = new Creneau();
        $creneau2->setEnseignant($enseignant1);
        $creneau2->setTitre($faker->text($maxNbChars = 20));
        $creneau2->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau2->setDateDebut(new DateTime('06/06/2020'));
        $creneau2->setDateFin(new DateTime('06/07/2020'));
        $creneau2->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro','ZoneGrisee','Evenement','Disponibilite')));

        $manager->persist($creneau2);

        $creneau3 = new Creneau();
        $creneau3->setEnseignant($enseignant2);
        $creneau3->setTitre($faker->text($maxNbChars = 20));
        $creneau3->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau3->setDateDebut(new DateTime('06/06/2020'));
        $creneau3->setDateFin(new DateTime('06/07/2020'));
        $creneau3->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro','ZoneGrisee','Evenement','Disponibilite')));

        $manager->persist($creneau3);

        $creneau4 = new Creneau();
        $creneau4->setEnseignant($enseignant3);
        $creneau4->setTitre($faker->text($maxNbChars = 20));
        $creneau4->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau4->setDateDebut(new DateTime('06/06/2020'));
        $creneau4->setDateFin(new DateTime('06/07/2020'));
        $creneau4->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro','ZoneGrisee','Evenement','Disponibilite')));

        $manager->persist($creneau4);

        $creneau5 = new Creneau();
        $creneau5->setEnseignant($enseignant4);
        $creneau5->setTitre($faker->text($maxNbChars = 20));
        $creneau5->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau5->setDateDebut(new DateTime('06/06/2020'));
        $creneau5->setDateFin(new DateTime('06/07/2020'));
        $creneau5->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro','ZoneGrisee','Evenement','Disponibilite')));

        $manager->persist($creneau5);

        $creneau6 = new Creneau();
        $creneau6->setEnseignant($enseignant5);
        $creneau6->setTitre($faker->text($maxNbChars = 20));
        $creneau6->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau6->setDateDebut(new DateTime('06/06/2020'));
        $creneau6->setDateFin(new DateTime('06/07/2020'));
        $creneau6->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro','ZoneGrisee','Evenement','Disponibilite')));

        $manager->persist($creneau6);

        $creneau7 = new Creneau();
        $creneau7->setEnseignant($enseignant8);
        $creneau7->setTitre($faker->text($maxNbChars = 20));
        $creneau7->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau7->setDateDebut(new DateTime('2020-06-08 17:00:00'));
        $creneau7->setDateFin(new DateTime('2020-06-08 18:00:00'));
        $creneau7->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro')));

        $manager->persist($creneau7);

        $creneau8 = new Creneau();
        $creneau8->setEnseignant($enseignant8);
        $creneau8->setTitre($faker->text($maxNbChars = 20));
        $creneau8->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau8->setDateDebut(new DateTime('2020-06-08 14:00:00'));
        $creneau8->setDateFin(new DateTime('2020-06-08 15:00:00'));
        $creneau8->setType($faker->randomElement($array = array ('ContraintePerso','ContraintePro')));

        $manager->persist($creneau8);

        $creneau9 = new Creneau();
        $creneau9->setEnseignant($enseignant8);
        $creneau9->setTitre($faker->text($maxNbChars = 20));
        $creneau9->setPrioOuPref($faker->randomElement($array = array ('SansPrio')));
        $creneau9->setDateDebut(new DateTime('2020-06-08 14:00:00'));
        $creneau9->setDateFin(new DateTime('2020-06-08 15:00:00'));
        $creneau9->setType($faker->randomElement($array = array ('ContrainteProPonctu')));

        $manager->persist($creneau9);

        $creneau10 = new Creneau();
        $creneau10->setEnseignant($enseignant3);
        $creneau10->setTitre($faker->text($maxNbChars = 20));
        $creneau10->setPrioOuPref($faker->randomElement($array = array ('Forte','Moyenne','Faible')));
        $creneau10->setDateDebut(new DateTime('2020-06-08 14:00:00'));
        $creneau10->setDateFin(new DateTime('2020-06-08 15:00:00'));
        $creneau10->setType($faker->randomElement($array = array ('Disponibilite')));

        $manager->persist($creneau10);

        $creneau11 = new Creneau();
        $creneau11->setEnseignant($enseignant3);
        $creneau11->setTitre($faker->text($maxNbChars = 20));
        $creneau11->setPrioOuPref($faker->randomElement($array = array ('SansPrio')));
        $creneau11->setDateDebut(new DateTime('06/06/2020'));
        $creneau11->setDateFin(new DateTime('06/07/2020'));
        $creneau11->setType($faker->randomElement($array = array ('ContrainteProPonctu')));

        $manager->persist($creneau11);

        // Création des logs $enseignants

        $logEnseignant1 = new LogEnseignant();
        $logEnseignant1->setDateLog(new DateTime('06/06/2020'));
        $logEnseignant1->setTypeAction($faker->randomElement($array = array ('Consultation','Ajout','Suppression','Modification')));
        $logEnseignant1->setZone($faker->randomElement($array = array ('Contrainte professionnelle et personnelle hebdomadaire','Contrainte professionnelle ponctuelle','Remarque contrainte professionnelle et personnelle hebdomaire','Remarque contrainte professionnelle ponctuelle')));
        $logEnseignant1->setDescription($faker->text($maxNbChars = 200));
        $logEnseignant1->setEnseignant($enseignant1);

        $manager->persist($logEnseignant1);



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
        $formulaireTitulaire->setTexteHebdomadaire("Si vous avez des contraintes professionnelles ainsi que des préferences personnelles hebdomadaires (bureau de direction le
mardi après-midi, créneau réservé à la recherche le jeudi après-midi, enseignement dans un autre département, etc.) merci de
les préciser :"
);
        $formulaireTitulaire->setTextePonctuel("Si vous avez des contraintes professionnelles spécifques déjà identifées pour les semaines de la rentrée (conférence,
déplacement ACDI, etc.) merci de les préciser :"
);
        $formulaireTitulaire->setRemarquesHebdoActives(true);
        $formulaireTitulaire->setRemarquesPonctuelActives(true);
        $formulaireTitulaire->setEstOuvert(true);

        $formulaireTitulaire->setQuantiteProForte(2);
        $formulaireTitulaire->setDureeProForte(60);
        $formulaireTitulaire->setQuantiteProMoy(2);
        $formulaireTitulaire->setDureeProMoy(60);
        $formulaireTitulaire->setQuantiteProFaible(2);
        $formulaireTitulaire->setDureeProFaible(60);

        $formulaireTitulaire->setQuantitePersForte(2);
        $formulaireTitulaire->setDureePersForte(60);
        $formulaireTitulaire->setQuantitePersMoy(2);
        $formulaireTitulaire->setDureePersMoy(60);
        $formulaireTitulaire->setQuantitePersFaible(2);
        $formulaireTitulaire->setDureePersFaible(60);
        $formulaireTitulaire->setHeureDebutCalendrier(new DateTime('08:00:00'));
        $formulaireTitulaire->setHeureFinCalendrier(new DateTime('18:00:00'));
        $formulaireTitulaire->setAutoriserTitreVideContraintePerso(true);
        $formulaireTitulaire->setAnneeUniversitaire("2020-2021");


        $manager->persist($formulaireTitulaire);

        $formulaireVacataire= new FormulaireVacataire();
        $formulaireVacataire->setEchelleCalendrier(60);
        $formulaireVacataire->setTexteHebdomadaire("

La durée des enseignements à l'IUT est câlée sur le modèle suivant :
Cours : 01h00 travaux dirigés / travaux pratiques : 01h30
A noter : il n'est pas possible de placer des enseignements le jeudi après-midi en DUT informatique (cette demi-journée est
réservée au sport universitaire) ; il est par contre possible de placer des enseignements le jeudi après-midi en Licence
professionnelle Métiers du numérique et Licence professionnelle Programmation avancée.
Nous vous remercions de prévoir sufsamment de créneaux libres pour placer vos enseignements hebdomadaires. Plus
vous nous proposerez de créneaux, plus nous aurons de possibilités pour essayer de satisfaire les contraintes des uns et
des autres.
Indiquez dans le tableau ci-dessous les créneaux sur lesquels vous êtes disponible pour venir enseigner à l'IUT :
");
        $formulaireVacataire->setTextePonctuel("Si vous avez des contraintes professionnelles spécifques déjà identifées pour les semaines de la rentrée ou futures (conférence,
déplacement ACDI, etc.) merci de les préciser :
");
        $formulaireVacataire->setRemarquesHebdoActives(true);
        $formulaireVacataire->setRemarquesPonctuelActives(true);
        $formulaireVacataire->setEstOuvert(true);
        $formulaireVacataire->setHeureDebutCalendrier(new DateTime('08:00:00'));
        $formulaireVacataire->setHeureFinCalendrier(new DateTime('18:00:00'));
        $formulaireVacataire->setAnneeUniversitaire("2020-2021");



        $manager->persist($formulaireVacataire);


        // Envoyer les données en BD
        $manager->flush();

    }
}
