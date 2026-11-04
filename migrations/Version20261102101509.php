<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261102101509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gps_point (id INT AUTO_INCREMENT NOT NULL, latitude NUMERIC(10, 7) NOT NULL, longitude NUMERIC(10, 7) NOT NULL, city VARCHAR(50) NOT NULL, has_asteroid TINYINT(1) NOT NULL, activation_radius INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planet ADD gps_point_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA5CCA2B0EC FOREIGN KEY (gps_point_id) REFERENCES gps_point (id)');
        $this->addSql('CREATE INDEX IDX_68136AA5CCA2B0EC ON planet (gps_point_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA5CCA2B0EC');
        $this->addSql('DROP TABLE gps_point');
        $this->addSql('DROP INDEX IDX_68136AA5CCA2B0EC ON planet');
        $this->addSql('ALTER TABLE planet DROP gps_point_id');
    }
}
