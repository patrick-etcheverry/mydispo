<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605173514 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formulaire_titulaire ADD heure_debut_calendrier TIME NOT NULL, ADD heure_fin_calendrier TIME NOT NULL');
        $this->addSql('ALTER TABLE formulaire_vacataire ADD heure_debut_calendrier TIME NOT NULL, ADD heure_fin_calendrier TIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formulaire_titulaire DROP heure_debut_calendrier, DROP heure_fin_calendrier');
        $this->addSql('ALTER TABLE formulaire_vacataire DROP heure_debut_calendrier, DROP heure_fin_calendrier');
    }
}
