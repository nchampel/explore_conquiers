<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261012134111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(75) NOT NULL, type VARCHAR(75) NOT NULL, metal INT NOT NULL, crystal INT NOT NULL, deuterium INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE constant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, galaxy INT NOT NULL, g_system INT NOT NULL, position INT NOT NULL, energy_production INT NOT NULL, deuterium_production INT NOT NULL, INDEX IDX_68136AA5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_building (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, planet_id INT NOT NULL, level INT NOT NULL, INDEX IDX_922654004D2A7E12 (building_id), INDEX IDX_92265400A25E9820 (planet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_resource (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, fields_number INT NOT NULL, metal INT NOT NULL, crystal INT NOT NULL, deuterium INT NOT NULL, satellite INT NOT NULL, INDEX IDX_CFD8C1C2A25E9820 (planet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE planet_building ADD CONSTRAINT FK_922654004D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE planet_building ADD CONSTRAINT FK_92265400A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_resource ADD CONSTRAINT FK_CFD8C1C2A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA5A76ED395');
        $this->addSql('ALTER TABLE planet_building DROP FOREIGN KEY FK_922654004D2A7E12');
        $this->addSql('ALTER TABLE planet_building DROP FOREIGN KEY FK_92265400A25E9820');
        $this->addSql('ALTER TABLE planet_resource DROP FOREIGN KEY FK_CFD8C1C2A25E9820');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE constant');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP TABLE planet_building');
        $this->addSql('DROP TABLE planet_resource');
        $this->addSql('DROP TABLE user');
    }
}
