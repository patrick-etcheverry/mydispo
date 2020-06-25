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

          //UTILISATEURS DE TEST POUR L'APERCU DES FORMULAIRES
        $titulaireTest = new Enseignant();
        $titulaireTest->setNom('Titulaire');
        $titulaireTest->setPrenom('Test');
        $titulaireTest->setStatut('Titulaire');
        $titulaireTest->setMail($_ENV['ADMIN_MAIL']);
        $titulaireTest->setToken($titulaireTest->genererToken());
        $titulaireTest->setEnSommeil(false);
        $titulaireTest->setSaisieFaite(true);
        $titulaireTest->setDateSaisie(new DateTime('12/07/2020'));
        $titulaireTest->setDateDerniereModif(null);
        $titulaireTest->setMailRelanceRecu(true);
        $titulaireTest->setPremierMailRecu(true);
        $titulaireTest->setDatePremierMail(new DateTime('05/06/2020'));
        $titulaireTest->setDateDerniereRelance(new DateTime('12/07/2020'));
        $titulaireTest->setNbRelance(0);

        $manager->persist($titulaireTest);

        $vacataireTest = new Enseignant();
        $vacataireTest->setNom('Vacataire');
        $vacataireTest->setPrenom('Test');
        $vacataireTest->setStatut('Vacataire');
        $vacataireTest->setMail($_ENV['ADMIN_MAIL']);
        $vacataireTest->setToken($vacataireTest->genererToken());
        $vacataireTest->setEnSommeil(false);
        $vacataireTest->setSaisieFaite(true);
        $vacataireTest->setDateSaisie(new DateTime('12/07/2020'));
        $vacataireTest->setDateDerniereModif(null);
        $vacataireTest->setMailRelanceRecu(true);
        $vacataireTest->setPremierMailRecu(true);
        $vacataireTest->setDatePremierMail(new DateTime('05/06/2020'));
        $vacataireTest->setDateDerniereRelance(new DateTime('12/07/2020'));
        $vacataireTest->setNbRelance(0);

        $manager->persist($vacataireTest);

        // Import d'enseignant depuis un fichier
        $NumeroLigne = 0;



        if (($fichier = fopen("./public/Importation-Enseignants/Enseignants.csv", "r")) !== FALSE) {
            while (($donnees = fgetcsv($fichier, 1000, ",")) !== FALSE) {
              $NumeroLigne++;
              $enseignant = "enseignant".$NumeroLigne;

              $enseignant = new Enseignant();
              $enseignant->setNom($donnees[0]);
              $enseignant->setPrenom($donnees[1]);
              $enseignant->setMail($donnees[2]);
              $enseignant->setStatut($donnees[3]);
              $enseignant->setEnSommeil(false);
              $tokenEnClair = $enseignant->genererToken();
              $enseignant->setToken($enseignant->genererToken());
              $enseignant->setSaisieFaite(false);
              $enseignant->setDateSaisie(null);
              $enseignant->setDateDerniereModif(null);
              $enseignant->setMailRelanceRecu(false);
              $enseignant->setPremierMailRecu(false);
              $enseignant->setDatePremierMail(null);
              $enseignant->setDateDerniereRelance(null);
              $enseignant->setNbRelance(0);
              /*for ($i=4; $i < sizeof($donnees) ; $i++) {
                if(isset($donnees[i])){
                  $enseignant->addFormation($donnees[i]);
                }*/




              $manager->persist($enseignant);
              $manager->flush();
        }
        fclose($fichier);
        }


        // Création des modèles de mails
        $modeleMailPremier = new ModeleMail();
        $modeleMailPremier->setNom('Mail premier contact');
        $modeleMailPremier->setSujet('Saisie de vos contraintes et disponibilités IUT Anglet');
        $modeleMailPremier->setContenu(
          'Bonjour [*PRENOM*] [*NOM*],

           L\'année universitaire 2020-2021 approche !
           Je vous contact aujourd\'hui dans le but de saisir vos disponibilités et contraintes pour l\'année 2020-2021.
           Pour trouverez en suivant un lien vous permettant de saisir vos contraintes et disponibilités sur notre nouveau site web.

           Lien : [*LIEN*]

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
          'Bonjour [*PRENOM*] [*NOM*],

           L\'année universitaire 2020-2021 approche !
           Je vous relance suite à la saisie de vos disponibilités et contraintes pour l\'universitaire 2020-2021.
           Veuillez-vous rendre sur le lien ci-joint pour effectuer vos saisies.

           Lien : [*LIEN*]

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

            'Bonjour [*PRENOM*] [*NOM*],

             Vous avez oublié votre lien personnalisé pour la saisie de vos contraintes et disponibilités pour l\'année 2020-2021 ?

             Voici votre lien : [*LIEN*]

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

        $formationDUT = new Formation();
        $formationDUT->setNomCourt("DUT Info");
        $formationDUT->setNomLong("Diplôme universitaire de technologie");

        $manager->persist($formationDUT);


        $formationLP =  new Formation();
        $formationLP->setNomCourt("LP Info");
        $formationLP->setNomLong("License professionnelle informatique");

        $manager->persist($formationLP);


        // Envoyer les données en BD
        $manager->flush();

    }
}
