<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260426234334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_user ADD photo VARCHAR(255) DEFAULT NULL, ADD numero_securite_sociale VARCHAR(30) DEFAULT NULL, ADD personne_contact VARCHAR(150) DEFAULT NULL, ADD telephone_personne_contact VARCHAR(20) DEFAULT NULL, ADD medecin_traitant VARCHAR(150) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_user DROP photo, DROP numero_securite_sociale, DROP personne_contact, DROP telephone_personne_contact, DROP medecin_traitant');
    }
}
