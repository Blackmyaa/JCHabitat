<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017112946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, roles JSON NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_32EB52E8E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ballon (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(100) NOT NULL, modele VARCHAR(100) NOT NULL, capacite_litres INT NOT NULL, type_energie VARCHAR(50) NOT NULL, prix_ht NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, email VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, administrateur_id INT NOT NULL, numero_devis VARCHAR(50) NOT NULL, date_creation DATETIME NOT NULL, statut VARCHAR(20) NOT NULL, montant_total NUMERIC(10, 2) NOT NULL, is_read TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8B27C52B2478EE16 (numero_devis), INDEX IDX_8B27C52B19EB6921 (client_id), INDEX IDX_8B27C52B7EE5403C (administrateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_ballon (id INT AUTO_INCREMENT NOT NULL, devis_id INT NOT NULL, ballon_id INT NOT NULL, quantite INT NOT NULL, prix_ballon NUMERIC(10, 2) NOT NULL, INDEX IDX_14EBD70B41DEFADA (devis_id), INDEX IDX_14EBD70BE2668897 (ballon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_prestation (id INT AUTO_INCREMENT NOT NULL, devis_id INT NOT NULL, prestation_id INT NOT NULL, quantite INT NOT NULL, prix_prestation NUMERIC(10, 2) NOT NULL, INDEX IDX_E169C44541DEFADA (devis_id), INDEX IDX_E169C4459E45C554 (prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, prix_ht NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B7EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE devis_ballon ADD CONSTRAINT FK_14EBD70B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE devis_ballon ADD CONSTRAINT FK_14EBD70BE2668897 FOREIGN KEY (ballon_id) REFERENCES ballon (id)');
        $this->addSql('ALTER TABLE devis_prestation ADD CONSTRAINT FK_E169C44541DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE devis_prestation ADD CONSTRAINT FK_E169C4459E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B7EE5403C');
        $this->addSql('ALTER TABLE devis_ballon DROP FOREIGN KEY FK_14EBD70B41DEFADA');
        $this->addSql('ALTER TABLE devis_ballon DROP FOREIGN KEY FK_14EBD70BE2668897');
        $this->addSql('ALTER TABLE devis_prestation DROP FOREIGN KEY FK_E169C44541DEFADA');
        $this->addSql('ALTER TABLE devis_prestation DROP FOREIGN KEY FK_E169C4459E45C554');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE ballon');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_ballon');
        $this->addSql('DROP TABLE devis_prestation');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
