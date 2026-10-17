<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261013154835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE defence (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(75) NOT NULL, type VARCHAR(75) NOT NULL, metal INT NOT NULL, crystal INT NOT NULL, deuterium INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_army (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_defence (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, defence_id INT NOT NULL, number INT NOT NULL, INDEX IDX_444B0A3A25E9820 (planet_id), INDEX IDX_444B0A39BECBA4D (defence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet_vessel (id INT AUTO_INCREMENT NOT NULL, planet_id INT NOT NULL, vessel_id INT NOT NULL, number INT NOT NULL, INDEX IDX_D2C5D960A25E9820 (planet_id), INDEX IDX_D2C5D96014AF1953 (vessel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vessel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(75) NOT NULL, type VARCHAR(75) NOT NULL, metal INT NOT NULL, crystal INT NOT NULL, deuterium INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planet_defence ADD CONSTRAINT FK_444B0A3A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_defence ADD CONSTRAINT FK_444B0A39BECBA4D FOREIGN KEY (defence_id) REFERENCES defence (id)');
        $this->addSql('ALTER TABLE planet_vessel ADD CONSTRAINT FK_D2C5D960A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('ALTER TABLE planet_vessel ADD CONSTRAINT FK_D2C5D96014AF1953 FOREIGN KEY (vessel_id) REFERENCES vessel (id)');
        $this->addSql('ALTER TABLE planet_resource DROP fields_number');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_defence DROP FOREIGN KEY FK_444B0A3A25E9820');
        $this->addSql('ALTER TABLE planet_defence DROP FOREIGN KEY FK_444B0A39BECBA4D');
        $this->addSql('ALTER TABLE planet_vessel DROP FOREIGN KEY FK_D2C5D960A25E9820');
        $this->addSql('ALTER TABLE planet_vessel DROP FOREIGN KEY FK_D2C5D96014AF1953');
        $this->addSql('DROP TABLE defence');
        $this->addSql('DROP TABLE planet_army');
        $this->addSql('DROP TABLE planet_defence');
        $this->addSql('DROP TABLE planet_vessel');
        $this->addSql('DROP TABLE vessel');
        $this->addSql('ALTER TABLE planet_resource ADD fields_number INT NOT NULL');
    }
}
