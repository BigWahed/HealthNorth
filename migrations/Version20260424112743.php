<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260424112743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_naissance DATE NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, type VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, date_prescription DATE NOT NULL, contenu LONGTEXT NOT NULL, patient_id INT NOT NULL, professionnel_id INT NOT NULL, INDEX IDX_1FBFB8D96B899279 (patient_id), INDEX IDX_1FBFB8D98A49CC82 (professionnel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prise_medicament (id INT AUTO_INCREMENT NOT NULL, posologie VARCHAR(255) NOT NULL, frequence VARCHAR(255) NOT NULL, moment_prise VARCHAR(255) NOT NULL, patient_id INT NOT NULL, medicament_id INT NOT NULL, prescription_id INT NOT NULL, INDEX IDX_9A13DBE46B899279 (patient_id), INDEX IDX_9A13DBE4AB0D61F7 (medicament_id), INDEX IDX_9A13DBE493DB413D (prescription_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, date_heure DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, patient_id INT NOT NULL, professionnel_id INT NOT NULL, etablissement_id INT NOT NULL, type_intervention_id INT NOT NULL, INDEX IDX_65E8AA0A6B899279 (patient_id), INDEX IDX_65E8AA0A8A49CC82 (professionnel_id), INDEX IDX_65E8AA0AFF631228 (etablissement_id), INDEX IDX_65E8AA0A799AAC17 (type_intervention_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE resultat_analyse (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(150) NOT NULL, type_analyse VARCHAR(100) NOT NULL, date_analyse DATE NOT NULL, statut VARCHAR(50) NOT NULL, commentaire LONGTEXT DEFAULT NULL, patient_id INT NOT NULL, INDEX IDX_20A9B04B6B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_intervention (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D96B899279 FOREIGN KEY (patient_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D98A49CC82 FOREIGN KEY (professionnel_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE prise_medicament ADD CONSTRAINT FK_9A13DBE46B899279 FOREIGN KEY (patient_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE prise_medicament ADD CONSTRAINT FK_9A13DBE4AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE prise_medicament ADD CONSTRAINT FK_9A13DBE493DB413D FOREIGN KEY (prescription_id) REFERENCES prescription (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A8A49CC82 FOREIGN KEY (professionnel_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A799AAC17 FOREIGN KEY (type_intervention_id) REFERENCES type_intervention (id)');
        $this->addSql('ALTER TABLE resultat_analyse ADD CONSTRAINT FK_20A9B04B6B899279 FOREIGN KEY (patient_id) REFERENCES app_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D96B899279');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D98A49CC82');
        $this->addSql('ALTER TABLE prise_medicament DROP FOREIGN KEY FK_9A13DBE46B899279');
        $this->addSql('ALTER TABLE prise_medicament DROP FOREIGN KEY FK_9A13DBE4AB0D61F7');
        $this->addSql('ALTER TABLE prise_medicament DROP FOREIGN KEY FK_9A13DBE493DB413D');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A8A49CC82');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AFF631228');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A799AAC17');
        $this->addSql('ALTER TABLE resultat_analyse DROP FOREIGN KEY FK_20A9B04B6B899279');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE prise_medicament');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE resultat_analyse');
        $this->addSql('DROP TABLE type_intervention');
    }
}
