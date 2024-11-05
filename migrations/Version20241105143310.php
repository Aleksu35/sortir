<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105143310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON participant');
        $this->addSql('ALTER TABLE participant ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, DROP roles, DROP pseudo, DROP nom, DROP prenom, DROP telephone, DROP actif, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD etat VARCHAR(255) DEFAULT NULL, ADD published TINYINT(1) NOT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE duree duree VARCHAR(255) DEFAULT NULL, CHANGE nb_inscriptions_max nb_inscription_max INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant ADD roles JSON NOT NULL, ADD pseudo VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD telephone VARCHAR(10) NOT NULL, ADD actif TINYINT(1) DEFAULT NULL, DROP firstname, DROP lastname, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON participant (email)');
        $this->addSql('ALTER TABLE sortie DROP etat, DROP published, CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE duree duree INT DEFAULT NULL, CHANGE nb_inscription_max nb_inscriptions_max INT DEFAULT NULL');
    }
}
