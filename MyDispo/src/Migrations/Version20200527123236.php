<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527123236 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE formulaire_titulaire (id INT AUTO_INCREMENT NOT NULL, echelle_calendrier INT NOT NULL, texte_hebdomadaire VARCHAR(3000) NOT NULL, texte_ponctuel VARCHAR(3000) NOT NULL, remarques_hebdo_actives TINYINT(1) NOT NULL, remarques_ponctuel_actives TINYINT(1) NOT NULL, est_ouvert TINYINT(1) NOT NULL, quantite_pro_forte INT NOT NULL, quantite_pro_moy INT NOT NULL, quantite_pro_faible INT NOT NULL, quantite_pers_forte INT NOT NULL, quantite_pers_moy INT NOT NULL, quantite_pers_faible INT NOT NULL, duree_pro_forte INT NOT NULL, duree_pro_moy INT NOT NULL, duree_pro_faible INT NOT NULL, duree_pers_forte INT NOT NULL, duree_pers_moy INT NOT NULL, duree_pers_faible INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire_vacataire (id INT AUTO_INCREMENT NOT NULL, echelle_calendrier INT NOT NULL, texte_hebdomadaire VARCHAR(3000) NOT NULL, texte_ponctuel VARCHAR(3000) NOT NULL, remarques_hebdo_actives TINYINT(1) NOT NULL, remarques_ponctuel_actives TINYINT(1) NOT NULL, est_ouvert TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE formulaire');
        $this->addSql('ALTER TABLE enseignant ADD date_saisie DATETIME DEFAULT NULL, ADD date_derniere_modif DATETIME DEFAULT NULL, ADD premier_mail_recu TINYINT(1) NOT NULL, ADD mail_relance_recu TINYINT(1) NOT NULL, ADD date_premier_mail DATETIME DEFAULT NULL, ADD date_derniere_relance DATETIME DEFAULT NULL, ADD nb_relance INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE evenement (title VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, start VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, end VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, UNIQUE INDEX title (title)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE formulaire (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT NOT NULL, echelle_calendrier INT NOT NULL, texte_hebdomadaire VARCHAR(2000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, texte_ponctuel VARCHAR(2000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, remarques_hebdo_actives TINYINT(1) NOT NULL, remarques_ponctuel_actives TINYINT(1) NOT NULL, est_ouvert TINYINT(1) NOT NULL, quantite_pro_forte INT DEFAULT NULL, quantite_pro_moy INT DEFAULT NULL, quantite_pro_faible INT DEFAULT NULL, quantite_pers_forte INT DEFAULT NULL, quantite_pers_moy INT DEFAULT NULL, quantite_pers_faible INT DEFAULT NULL, duree_pro_forte INT DEFAULT NULL, duree_pro_moy INT DEFAULT NULL, duree_pro_faible INT DEFAULT NULL, duree_pers_forte INT DEFAULT NULL, duree_pers_moy INT DEFAULT NULL, duree_pers_faible INT DEFAULT NULL, INDEX IDX_5BDD01A8E455FCC0 (enseignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE formulaire ADD CONSTRAINT FK_5BDD01A8E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE formulaire_titulaire');
        $this->addSql('DROP TABLE formulaire_vacataire');
        $this->addSql('ALTER TABLE enseignant DROP date_saisie, DROP date_derniere_modif, DROP premier_mail_recu, DROP mail_relance_recu, DROP date_premier_mail, DROP date_derniere_relance, DROP nb_relance');
    }
}
