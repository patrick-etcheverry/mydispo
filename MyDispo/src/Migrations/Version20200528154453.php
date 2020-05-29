<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200528154453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formulaire_titulaire CHANGE duree_pro_forte duree_pro_forte TIME NOT NULL, CHANGE duree_pro_moy duree_pro_moy TIME NOT NULL, CHANGE duree_pro_faible duree_pro_faible TIME NOT NULL, CHANGE duree_pers_forte duree_pers_forte TIME NOT NULL, CHANGE duree_pers_moy duree_pers_moy TIME NOT NULL, CHANGE duree_pers_faible duree_pers_faible TIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formulaire_titulaire CHANGE duree_pro_forte duree_pro_forte INT NOT NULL, CHANGE duree_pro_moy duree_pro_moy INT NOT NULL, CHANGE duree_pro_faible duree_pro_faible INT NOT NULL, CHANGE duree_pers_forte duree_pers_forte INT NOT NULL, CHANGE duree_pers_moy duree_pers_moy INT NOT NULL, CHANGE duree_pers_faible duree_pers_faible INT NOT NULL');
    }
}
